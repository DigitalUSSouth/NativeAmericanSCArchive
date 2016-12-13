from django.apps import AppConfig
#from django.contrib.auth.models import User

class RegistrationConfig(AppConfig):
    name = 'registration'
    verbose_name = 'User Registration'

#    def ready(self):
#        with open("/home/titus/Github/NativeAmericanSCArchive/Database/users.txt","w") as file:
#            users = User.objects.all()
#            for user in users:
#                file.write(user)