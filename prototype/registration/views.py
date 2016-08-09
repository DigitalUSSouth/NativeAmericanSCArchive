import abc

from django.conf import settings, global_settings

from django.shortcuts import render
from django.core.urlresolvers import reverse

from django.http import HttpResponseRedirect, JsonResponse, HttpResponse
from django.utils.http import is_safe_url

from django.contrib import messages

from django.contrib.auth import REDIRECT_FIELD_NAME, login, logout
from django.contrib.auth.models import User
from django.contrib.auth.mixins import (
    LoginRequiredMixin, UserPassesTestMixin, PermissionRequiredMixin,
)

from django.utils.decorators import method_decorator
from django.views.decorators.cache import never_cache
from django.views.decorators.csrf import csrf_protect
from django.views.decorators.debug import sensitive_post_parameters

from django.views.generic import View, FormView, ListView, UpdateView

from .forms import (BannedVerifyRegistrationForm, 
    BaseRegistrationForm, DualLoginForm)
from .decorators import login_allowed

from responses import (AjaxPostMixin, AjaxGetMixin, 
    AjaxView, FormResponse)

""" All class based views inheriting from FormView, as you would expect
represent a view with a singular form. In your urls.py you should denote 
the view by ClassName.as_view(). The as_view() method of the class will
actually return the view "representation" of the class. 

You can view the class details here: 

    https://ccbv.co.uk/projects/Django/1.9/django.views.generic.edit/FormView/

and the respective overloaded methods. """

""" 

    **** YOU CANNOT STACK UserPassesTextMixin WITH INHERITANCE **** 

"""

class ProtectedNASCAMixin(UserPassesTestMixin):

    login_url = "/accounts/login/"
    redirect_field_name = "next"
    redirect_unauthenticated_users = True
    raise_exception = False

    def test_func(self):

        user = self.request.user
        return all([user.is_superuser or user.is_staff,])

class AccountManagementMixin(ProtectedNASCAMixin, PermissionRequiredMixin, View):

    """ This is arguably useless, but it's just to allow a user to 
    splice the delegate should they wish to in the future... """

    pass

class BaseAccountManagement(AccountManagementMixin, AjaxPostMixin, metaclass=abc.ABCMeta):

    def check_mixin_attributes(self):

        """ FIX THIS FOR ACTUALLY CHECKING THE
        ATTRIBUTES """

        if not self.permission_required:
            raise AttributeError("You must set the " +
                "permission required attribute for " +
                "derivatives that use the " + 
                "'PermissionRequiredMixin'.")
    
    """ Need to re-order responses in order to fix this redundancy.."""

    def get_default_response(self):

        pass

    def get_success_message(self):

        return "You have successfully %s %s." % (self.msg_type, 
                                                 self.user.username)

    def get_json_response(self, ):

        return {'data': {'success': True, 
                         'msg': self.get_success_message(),},}

    @abc.abstractmethod
    def change_user(self):

        pass

    def post(self, request, pk):

        """ This is the method on the base class of all
        classes in "responses" AbstractResponse. """

        self.user = User.objects.get(pk=pk)
        self.change_user()
        return self.return_response()

class AccountApprovalView(BaseAccountManagement):

    permission_required = "user.can_add_user"
    msg_type = "approved"

    def change_user(self):

        self.user.is_active = True
        self.user.save()

class AccountDenyView(BaseAccountManagement):

    permission_required = "user.can_delete_user"
    msg_type = "deleted"

    def change_user(self, user):

        self.user.delete()

class LoginView(FormView):

    form_class = DualLoginForm
    success_url = "home" #Named url
    http_method_names = ['get', 'post']
    template_name = 'registration/login.html'

    @method_decorator(sensitive_post_parameters('password'))
    @method_decorator(never_cache)
    @method_decorator(csrf_protect)
    #If login isn't allowed we don't care about the others...
    @login_allowed
    def dispatch(self, *args, **kwargs):

        self.request.session.set_test_cookie()
        return super(LoginView, self).dispatch(*args, **kwargs)

    def form_valid(self, form):

        #form.user is the property on DualLoginForm

        """ The property is a descriptor, using @property on the
        method DualLoginForm.user(*args, **kwargs) sets the getter
        method of this property to be "user" and hence this method
        return self.user_cache -> user. We can access this by doing
        DualLoginForm.user where DualLoginForm would be an instance. """

        login(self.request, form.user)
        if self.request.session.test_cookie_worked():
            self.request.session.delete_test_cookie()
        return super(LoginView, self).form_valid(form)

    #This deff. doesn't have to be a property just using this descriptor
    #to shorten some nested attribute accessions....
    @property
    def redirect_name(self):

        return self.form_class.redirect_field_name

    #Nor does this...
    @property
    def next(self):

        full_path = self.request.get_full_path()
        if "?next=" in full_path:
            return full_path.split("?next=")[-1]
        elif self.redirect_name in self.request.POST:
            return self.request.POST[self.redirect_name]

    def get_redirect_url(self):

        return self.next if self.next else getattr(settings, 'LOGIN_REDIRECT_URL', False)
        
    def get_success_url(self):

        redirect = self.get_redirect_url()

        """ If the settings file has a redirect method we naturally want to use
         this redirect url as the main priority as the user has set this for
         a reason, other we check for a next method in a hidden input of the
         form itself in order to check for a next redirect path, otherwise 
         we use the base success url as a fallback. """

        if redirect and not is_safe_url(url=redirect, host=self.request.get_host):
            redirect = self.success_url

        """ If there's not a "/" in the url then we use reverse(base) as the
        base is a named url route 
            
            - A named url route uses name="*"
            
            - For url(r'^*..', view, name=*) patterns in urls.py

        reverse(name) will go get this named path and return the real url. """

        return redirect if "/" in redirect else reverse(redirect)

""" It should be noted here that for any user that doesn't have the
permissions for ProtectedNASCAMixin, then they will be redirected to
account/login since this is denoted as the attribute login_url.
However, since the user will already be authenticated this will 
redirect them to account/profile. """

class UserManagementDirectory(LoginRequiredMixin, ProtectedNASCAMixin, ListView):

    model = User
    context_object_name = "users"
    template_name = 'registration/directory.html'

    def get_queryset(self):

        queryset = super(UserManagementDirectory, self).get_queryset()

        """ We don't want a user that would be looking at the 
        management directory to be seeing users that have already been
        approved or see their own account.... """

        #We can simply chain the calls we need since the queryset 
        #wasn't evaluated yet... Queryset's are evaluated lazily 
        #unless you force their evaluation.

        return queryset.filter(is_active=False).\
                        exclude(pk=self.request.user.pk)

def logout_view(request):

    logout(request)
    messages.info(request, "You have been successfully logged out.")
    return HttpResponseRedirect("/")

def profile(request):

    return render(request, "registration/profile.html")
    
def registration_closed(request):

    return render(request, "registration/closed.html")

def register(request):

    form = BannedVerifyRegistrationForm(request.POST or None)
    if request.method == "POST":
        if form.is_valid():
            user = form.save(commit=False)
            print("USER", user)
            user.save()
            messages.info(request, "Your account has been submitted for approval. " +
                "You will not be able to restricted content until this process " +
                "has been completed.")
            return HttpResponseRedirect(reverse("login"))
    return render(request, 'registration/register.html', {'form': form})