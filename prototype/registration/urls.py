from django.conf.urls import include, url

from django.contrib.auth.views import login
from .views import register, registration_closed, LoginView, profile

from django.contrib.auth.forms import AuthenticationForm
from .forms import DualLoginForm

urlpatterns = [
    url(r'^register/$', register, name="register"),
    url(r'^registration-closed/$', registration_closed, name="registration_closed"),
    url(r'^login/$', LoginView.as_view(), name="login"),
    url(r'^profile/$', profile, name="profile"),
] 