from django.conf.urls import include, url

from .views import (register, registration_closed, 
	LoginView, logout_view, profile, UserManagementDirectory,
	AccountApprovalView, AccountDenyView,
)

from .forms import DualLoginForm

urlpatterns = [
    url(r'^register/$', register, name="register"),
    url(r'^registration-closed/$', registration_closed, name="registration_closed"),
    url(r'^login/$', LoginView.as_view(), name="login"),
    url(r'^logout/$', logout_view, name="logout"),
    url(r'^profile/$', profile, name="profile"),

    url(r'^approve/(?P<pk>\d+)/$', AccountApprovalView.as_view(), name='approve_account'),
    url(r'^deny/(?P<pk>\d+)/$', AccountDenyView.as_view(), name='deny_account'),
    #Not directly linked to signup / logout. These view are for
    #viewing / managing users in the registration / auth system.
    url(r'^$', UserManagementDirectory.as_view(), name="user_directory"),
] 