from django.contrib.auth.models import User


""" This is something that could be added / 
extended later... This in order to get around
having to bypass "authenticate" from 
django.contrib.auth. You **must** call
authenticate before calling login(*) as it
sets the "backend" attribute on the user.

However, since we are manually doing this in
the form we are in essence "hacking" the 
authentiation built-in by manually setting
the backend.

By providing a backend and adding it to the
settings file, authenticate will iterate
through all of the backends and will find 
this new backend we have set, and then set
the backend attribute to be this new
backend. """
 
class EmailOrUsernameBackend:

	pass

