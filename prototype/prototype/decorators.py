from functools import wraps
from django.core.exceptions import (PermissionDenied, 
	ObjectDoesNotExist)

""" These functions are for implementing row-level permissions
in Django. You may use a pluggable app to do this, but we can
eliminate further external dependencies by using simple decorator 
function in order to check user permissions w/ objects. """


""" This is just a useful utility function in order to wrap
two levels of decorators in a single decorator call. """

""" We could actually make a callable metaclass to handle any 
order of nesting.... Possible to do if the decorators become
unruly. """

def second_order(inner):
	def main(outer):
		def wrapper(func):
			wrapped = inner(outer(func))
			def wrapped_func(*args, **kwargs):
				return wrapped(*args, **kwargs)
			return wrapped_func
		return wrapper
	return main

def can_view():

	pass

def can_edit():

	pass