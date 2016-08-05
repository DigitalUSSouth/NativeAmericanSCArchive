from django.conf import settings, global_settings

from django.shortcuts import render
from django.core.urlresolvers import reverse

from django.http import HttpResponseRedirect, JsonResponse, HttpResponse
from django.utils.http import is_safe_url

from django.contrib import messages

from django.contrib.auth import REDIRECT_FIELD_NAME, login, logout
from django.contrib.auth.models import User
from django.contrib.auth.mixins import (AccessMixin, 
    PermissionRequiredMixin, UserPassesTestMixin)

from django.utils.decorators import method_decorator
from django.views.decorators.cache import never_cache
from django.views.decorators.csrf import csrf_protect
from django.views.decorators.debug import sensitive_post_parameters

from django.views.generic import FormView, ListView, UpdateView

from .forms import (BannedVerifyRegistrationForm, 
    BaseRegistrationForm, DualLoginForm)
from .decorators import login_allowed

""" All class based views inheriting from FormView, as you would expect
represent a view with a singular form. In your urls.py you should denote 
the view by ClassName.as_view(). The as_view() method of the class will
actually return the view "representation" of the class. 

You can view the class details here: 

    https://ccbv.co.uk/projects/Django/1.9/django.views.generic.edit/FormView/

and the respective overloaded methods. """

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

    @property
    def next(self):

        if self.redirect_name in self.request.POST:
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

        print("URL RETURNED BY GET_SUCCESS_URL",
            redirect if "/" not in redirect else reverse(redirect))
        return redirect if "/" not in redirect else reverse(redirect)


class UserDirectory(ListView):

    model = User
    context_object_name = "users"
    template_name = 'registration/directory.html'

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