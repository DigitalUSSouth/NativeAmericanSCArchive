from django.db import models
from django.contrib.auth.models import (
    BaseUserManager, AbstractBaseUser
)

from django.shortcuts import get_object_or_404

#NASCAUser has yet to be created.... Still waiting on specifications.

class NASCAUserManager(BaseUserManager):

	#We can arbitraily pass in the email or username here....
	#We will use the email by default.
	def approve_user(self, email, user_has_approval):

		if user_has_approval:
			
			user = get_object_or_404(self.model, email=email)
			user.is_active = True
			user.save()
		else:
			#If the user isn't approved then why do we need a database 
			#record....?

			#Could optionally skip this, to be determined. Perhaps user
			#input error??
			self.delete_user(email)

	def delete_user(self, email):

		user = get_object_or_404(self.model, email=email)
		del user

	# def create_user(self, *args, **kwargs):

		# pass