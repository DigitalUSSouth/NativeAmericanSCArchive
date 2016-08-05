from django.conf.urls import include, url

from .views import (register, registration_closed, 
	LoginView, logout_view, profile, UserDirectory,
)

from django.contrib.auth.forms import AuthenticationForm
from .forms import DualLoginForm

urlpatterns = [
    url(r'^register/$', register, name="register"),
    url(r'^registration-closed/$', registration_closed, name="registration_closed"),
    url(r'^login/$', LoginView.as_view(), name="login"),
    url(r'^logout/$', logout_view, name="logout"),
    url(r'^profile/$', profile, name="profile"),

    #Not directly linked to signup / logout. These view are for
    #viewing / managing users in the registration / auth system.
    url(r'^$', UserDirectory.as_view(), name="user_directory"),
] 