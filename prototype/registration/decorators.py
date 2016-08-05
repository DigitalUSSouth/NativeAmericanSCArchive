from functools import wraps

from django.conf import settings
from django.core.exceptions import PermissionDenied

from django.contrib import messages

from django.http import HttpResponseRedirect
from django.core.urlresolvers import reverse

def login_allowed(func):
	@wraps(func)
	def wrapper(cls, request, *args, **kwargs):
		if request is not None:
			if request.user.is_authenticated:
				messages.info(request, "You are already authenticated.")
				return HttpResponseRedirect(reverse("profile"))
			if not getattr(settings, 'LOGIN_CLOSED', False):
				return func(cls, request, *args, **kwargs)
			raise PermissionDenied
		return func(cls, request, *args, **kwargs)
	return wrapper