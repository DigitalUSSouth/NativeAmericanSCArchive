from functools import wraps

from django.conf import settings
from django.core.exceptions import PermissionDenied

from django.contrib import messages

from django.http import HttpResponseRedirect
from django.core.urlresolvers import reverse

""" This is just a simple decorator that is
meant to be used with the 'dispatch' method for
classed based view. Specifically, the class based
login view. This determines if the user is already
logged in and if they are authenticated it redirects
them. More, importantly it determines if logins are
even allowed at all. This is helpful if you want
to shutdown users being able to access content
temporarily. You can denote this by setting the
'LOGIN_CLOSED' setting in settings.py file to 'True'.
"""

def login_allowed(func):
	@wraps(func)
	def wrapper(cls, request, *args, **kwargs):

		""" Not the func(cls, *, *) you CANNOT use
		cls.func as you would self.(method_name) inside
		the class. This will raise an attribute error
		becuase the class has nothing named 'func' in
		the namespace. Alternatively, you could
		use getattr(cls, func.__name__) but this is
		overly verbose. """

		if request is not None:
			if request.user.is_authenticated():
				messages.info(request, "You are already authenticated.")
				return HttpResponseRedirect(reverse("profile"))
			if not getattr(settings, 'LOGIN_CLOSED', False):
				return func(cls, request, *args, **kwargs)
			raise PermissionDenied
		return func(cls, request, *args, **kwargs)
	return wrapper