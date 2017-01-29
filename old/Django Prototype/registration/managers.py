from django.db import models
from django.contrib.auth.models import (
    BaseUserManager, AbstractBaseUser
)

from django.shortcuts import get_object_or_404

#NASCAUser has yet to be created.... Still waiting on specifications.

""" This is to be implemented with the user model still to 
(potentially) be created, and then instead of the 

change_user abstract method the user manager can simply be called
by doing "model.objects.approve_user(type)" to approve or 
delete the user conditionally. """

class NASCAUserManager(BaseUserManager):

	#We can arbitraily pass in the email or username here....
	#We will use the email by default.
	def approve_user(self, identifiers, user_has_approval):

		if user_has_approval:
			
			user = get_object_or_404(self.model, **identifiers)
			user.is_active = True
			user.save()
		else:
			#If the user isn't approved then why do we need a database 
			#record....?

			#Could optionally skip this, to be determined. Perhaps user
			#input error??
			self.delete_user(identifiers)

	def delete_user(self, identifiers={}):

		user = get_object_or_404(self.model, **identifiers)
		del user

	# def create_user(self, *args, **kwargs):

		# pass