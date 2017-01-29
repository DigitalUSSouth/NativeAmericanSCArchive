from functools import wraps, partial
import itertools, inspect, types
import collections

#Calling this is a bit of overhead....
#since you have to call make_params ->
#get_parameters + create the coll object,
#but, every call after this will result in
#O(1) lookup. So, I think it's worth it if
#this was to potentially be called multiple
#time for the duration of the main Interface
#instance.
def check_collection_setting(attribute):
	def wrapper(func):
		@wraps(func)
		def inner_call(cls, alias, *args, **kwargs):
			salias = alias[1:]
			if salias in cls.collection_dict:
				coll = cls.collection_dict[salias]
				if hasattr(coll, attribute):
					return getattr(coll, attribute)
				else:
					response = func(cls, alias, *args, **kwargs)
					setattr(coll, attribute, response)
					return response
			else:
				coll = cls.make_collection_from_params(alias)
				cls.collection_dict[salias] = coll
				response = func(cls, alias, *args, **kwargs)
				setattr(coll, attribute, response)
				return response
		return inner_call
	return wrapper

#Simply checks if the collection is in the
#dict or not, if not then it makes it for 
#future O(1) lookup time....
def check_collection(func):
	@wraps(func)
	def wrapper(cls, alias, *args, **kwargs):

		salias = alias[1:]
		if not isinstance(cls.collections, collections.OrderedDict):
			if salias not in cls.collection_dict:
				#NOTE: This can fail if the alias is wrong.......
				collection = cls.make_collection_from_params(alias)
				cls.collection_dict[salias] = collection
			collection = cls.collection_dict[salias]
		else:
			collection = cls.make_collection_from_params(alias)
			cls.collection_dict[salias] = collection
		return func(cls, collection, *args, **kwargs)
	return wrapper

#We are taking all of the "collection-level" functions
#from the base Interface class and copying them over to
#Collection where they should technically be. However, we
#want to keep the API wrapper as open as possible hence they
#are also on the main interface and there is no need to 
#duplicate the code for. every. single. function.....
def copy_api_methods(methods):
	def wrapper(cls):
		def init(api, **kwargs):
			alias = kwargs.get('alias')
			for method in methods:
				par_method = getattr(api, method)
				#Fairly redundant to be calling methods on a collection 
				#instance with names like get_collection_* don't
				#you think....?
				if '_collection' in method:
					name = method.replace('_collection', '')
				else:
					name = method
				setattr(cls, name, partial(par_method, alias=alias))
			return cls(api, **kwargs)
		return init
	return wrapper

def check_if_enabled(func):
	name = func.__name__
	@wraps(func)
	def wrapper(*args, **kwargs):
		response = func(*args, **kwargs)
		# If this isn't enabled for the location 
		#(url / port) provided it returns a json
		#dict of {'enabled': 0}...
		if not response['enabled']:
			raise UserWarning("%s isn't currently enabled " % 
				name + "for the location provided.")
		return response
	return wrapper

def optimize_collections(func):
	is_generator = inspect.isgeneratorfunction(func)
	@wraps(func)
	def wrapper(cls, *args, **kwargs):
		#This is the OrderedDict we have built over time....
		if not isinstance(cls.collections, types.GeneratorType):
			#The only function that would have an arg
			#is the retrieve_collection function in 
			#which case we want to actually return
			#the dictionary item.....
			if args:
				alias = args[0]
				return cls.collections[alias]
			return cls.collections
		cls.collections, generator = itertools.tee(cls.collections, 2)
		response = func(cls, *args, generator=generator, **kwargs)
		#This means retrieve_entire_hierarchy was called, and through
		#the call of check_collecion_dict the 
		if is_generator:
			#This this will be an empty dict at 
			#first... but it will be filed upon
			#each yielded value of the generator

			#The obvious caveat here is that a user
			#could manually raise StopIteration and 
			#prevent the full collection hierarchy
			#from being redistributed into the dict...
			cls.collections = cls.collection_dict
		return response
	return wrapper