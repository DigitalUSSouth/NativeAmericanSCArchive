from functools import partial
from heapq import nlargest
import os

from django.contrib.contenttypes.models import ContentType

#This for getting the sum of the n-largest elements of a "list" of two-tuples
#for a charfields choieces keyword in combination with a MultiSelectField's
#max-choices attribute, since we want the maximim length to be the maximum
#between the largest values for the number of choices available set on the
#field. The list of tuples is y, num_choices is x.


"""
Time complexity of num_largest in.....

								Python 2:

In Python 2, the `map` built-in function returns a list, and the results are
actually evaluated. So, this is evaluated in O(n) time since the function passed
as the first argument to map must be applied to the iterable passed as the 
second argument n times.

In Python 3, 'map' no longer returns a list / has direct evaluation, instead the
would-be results are yielded and 'map' returns a map object which is an iterable
that "produces" these results.

sum(*) is O(n) time just from having to "read in" the n elements.

Since, we're in Python 3... map(len, (generator)) isn't evaluated until sum() is
in which case these would be the "n" elements mentioned above. Thus, these two
operations together are O(n) time.

(val[0] for val in nlargest(x, y)); nlargest(x, y) is O(t + n) tme.

Hence, in Python 3 this function (num_largest) is quadratic O(n^2) time and in
Python 2 it is cubic due to the evaluation of map directly.

"""

num_largest = lambda x, y: sum(map(len, (val[0] for val in nlargest(x, y))))

#This is for getting the maximum length for a list of two-tuples for
#a charfield's choices = ((*, *), ...) keyword.
get_max = lambda x: max(map(len, (y[0] for y in x)))

def check_size(value, limit, kb):

	size = value.size
	if (kb and size > limit * 1024) or (size > limit * 1024 * 1024):
		raise ValidationError('File must not exceed %.2f %s' % 
			(limit, 'kilobytes' if kb else 'megabytes'))

""" Wrapping with extra partials like this is redundant in Django 1.9+
they fixed the infinite recursion error when using singular partials in
the migrations file(s). However, we will keep this same schema for 
backwards compatibility. """

#We must partial this again in order to make it compatible
#with Django.
def size(limit, kb=False):

	#Must only accept the 'value' argument to be django compatible.

	return partial(check_size, limit=limit, kb=kb)

def check_ext(value, formats):

	ext = os.path.splitext(value.name)[1].lower()
	if ext not in formats:
		if len(formats) > 1:
			msg = ("Must be one of the following formats: " +
				",".join(formats))
		else:
			msg = "Must be a %s file!" % formats[0]
		raise ValidationError(msg)

def extension(formats=['.pdf']):

	valid_arg =  isinstance(formats, list) and len(formats)
	valid_formats = all(x.startswith('.') for x in formats)
	if not (valid_arg and valid_formats):
		raise ValueError("Invalid format argument. Format must be " +
			"a list of file extensions, with at least one extension " +
			"starting with '.' followed by the extension.\n\n" + 
			"e.g. - extension(formats=['.pdf'])")

	return partial(check_ext, formats=formats)

""" SHOULD BE USED FOR LOCAL DEBUGGING ONLY !!! """
def log_queries(func):
	def wrapper(*args, **kwargs):
		from django.db import connection
		result = func(*args, **kwargs)
		print(len(connection.queries))
		return result
	return wrapper

# def compress_img(image):

# 	img = Image.open(StringIO.StringIO(image.read()))
# 	if img.mode != 'RGB':
# 		img = img.convert('RGB')
# 	img.thumbnai(self.image.width / 1.5, self.image.height / 1.5, 
# 		Image.ANTIALIAS)
# 	output = StringIO.StringIO()
# 	img.save(output, format='JPEG', quality=70)
# 	output.seek(0)
# 	image = InMemoryUploadedFile(output, '')

class SelfAwareModel:

	def get_ct(self):

		return ContentType.objects.get_for_model(self)

	def get_app_label(self):

		return self.get_ct().app_label

	def get_ct_id(self):

		return self.get_ct().pk

	def get_model_name(self):

		return self.get_ct().model

""" Note: This must be provided AFTER the class based view.
Yes, this goes against the notion of a Mixin since you generally
put them on the leftmost side. However, consider this:"""

class PrefetchMixin(object):

	@log_queries
	def get_queryset(self):

		if not hasattr(self, 'prefetches'):
			raise AttributeError("You must define the prefetch " +
				"attribute on all classes that use the prefetch mixin.")

		if not isinstance(self.prefetches, (list, tuple)):
			raise ValueError("The prefetches attribute of PrefetchMixin" +
				" must be a list or tuple.")
		q = super().get_queryset().\
			   prefetch_related(*self.prefetches)
		return q

class PrefetchDetailMixin(object):

	allowed_attributes = ['lookup', 'queryset', 'to_attr']

	def build_kwargs(self):

		attrs = []

		for k, v in prefetches.items():
			if not isinstance(v, dict):
				raise ValueError("All values of keys in the " +
					"self.prefetches attribute must themselves " +
					"be dictionary instances. More specifically")
			#Note, this is the XOR bitwise operator.
			if not all((x in allowed_attributes for x in v.keys())):
				raise AttributeError("You have passed an invalid " +
					"attribute to the key %s in self.prefetches:" % k +
					' \n  The allowed attributes are %s' % 
					', '.join(self.allowed_attributes))
			attrs.append(Prefetch(k, **v))
		return attrs

	def get_queryset(self):

		if not hasattr(self, 'prefetches'):

			raise AttributeError("You must define the prefetch " +
				"attribute on all classes that use the prefetch mixin.")

		if not isinstance(prefetches, dict):
			raise ValueError('The prefetches attribute of ' +
				'PrefetchDetailMixin must be a dictionary ' +
				'instance.')

		return super().get_queryset().\
			   prefetch_related(*self.build_kwargs)