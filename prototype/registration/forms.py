from django.utils.translation import ugettext as _, ugettext_noop as _noop
from django.contrib.auth.models import User

from django import forms
from django.contrib.auth.forms import AuthenticationForm
from django.core.validators import validate_email

from django.contrib.auth import (authenticate, get_user_model, 
    password_validation,
)

from django.core.exceptions import ObjectDoesNotExist
#For catching validate_email
from django.core.exceptions import ValidationError

from crispy_forms.helper import FormHelper
from crispy_forms.layout import (Layout, Field, Div, Row, Submit,)

class ToSMixin(forms.Form):

    tos = forms.BooleanField(widget=forms.CheckboxInput,
            label=_("I have read and agree to the Terms of Service"),
            error_messages={'required': "Registration denied. You have " +
                "failed to accept the terms of service. You will not be " +
                "allowed to register until you have acknowledged the terms."})

""" We could wrap both of these with a metaclass / decorator. Take your pick to 
dynamically create these and reduce code repetition, but keeping it easy to 
understand with less magic for now... """

class VerifyPasswordMixin(forms.Form):

    confirm_password = forms.CharField(widget=forms.PasswordInput(),
        required=True, 
        error_messages={'required': _('You must verify your password.')})

    def clean_confirm_password(self):

        cdata = self.cleaned_data
        p1, p2 = cdata.get('password'), cdata.get('confirm_password')
        if p1 != p2:
            raise forms.ValidationError(_("Passwords must match."))
        return p2

class VerifyEmailMixin(forms.Form):

    confirm_email = forms.EmailField(required=True,
        error_messages={'required': _('You must verify your email.')})

    def clean_confirm_email(self):

        cdata = self.cleaned_data
        e1, e2 = cdata.get('email'), cdata.get('confirm_email')
        if e1 != e2:
            raise forms.ValidationError(_("Emails must match."))
        return e2

class BannedDomainMixin(forms.Form):

    allowed_domains = ['email.sc.edu',]

    def clean_email(self):

        email = self.cleaned_data['email']
        domain = email.split("@")[-1]
        if domain not in self.allowed_domains:
            raise forms.ValidationError(_("The domain you have used is " +
                "not a domain that is permitted for registration."))
        return email

class BaseRegistrationForm(forms.ModelForm, ToSMixin):

    """ By default we don't want any duplicate emails, meaning all emails
    that are used to register must be unique. """

    class Meta:

        fields = ('first_name', 'last_name', 'username', 'email', 'password',)
        model = User
        widgets = {'password': forms.PasswordInput()}

    def __init__(self, *args, **kwargs):

        super(BaseRegistrationForm, self).__init__(*args, **kwargs)

        """ COME UP WITH A CLEANER WAY """

        self.bases = [x.__name__ for x in self.__class__.__bases__]


        self.fields['first_name'].required = True
        self.fields['last_name'].required = True
        self.fields['email'].required = True

        self.helper = FormHelper(self)
        self.helper.layout = Layout(
            Row(
                Div(Field('first_name'), css_class='col-xs-6'),
                Div(Field('last_name'), css_class='col-xs-6'),
            ),
            Row(
                Div(Field('username'), css_class='col-xs-12'),
            ),
            Row(
                Div(Field('email'), css_class='col-xs-6'),
                Div(Field('password'), css_class='col-xs-6'),
            ),
            Row(
                Div(Field('tos'), css_class='col-xs-12'),
            ),
        )
        self.helper.add_input(
            Submit('submit', 'Register', css_class='btn btn-default',)
        )

    def clean_email(self):

        """ super() is needed so that BannedDomain and other mixins that
        inherently use clean_email or other override methods or also
        called by the method resolution order. """

        email = self.cleaned_data['email']
        if not email:
            raise forms.ValidationError(_("This field is required"))
        if User.objects.filter(email__iexact=email):
            raise forms.ValidationError(_("The email you use must be unique. " +
                "There is already a user registered with the email: %s" % email))
        
        """ We only want to check for banned domains, if we've bassed the
        base tests.... We don't modify email in anyway here, so returning
        the super().clean_email() object (i.e. - the email string) has
        no adverse side affects. """

        if 'BannedDomainMixin' in self.bases:
            return super().clean_email()
        else:
            return email

    def save(self, commit=False):

        """ We may want to override an action such that it affects all 
        derived forms from the BaseRegistrationFormClass. 

        e.g. - Sending a email to all admins to approve or deny applied
        users. (a signal could be used for this, but it's more overhead) """

        user = super(BaseRegistrationForm, self).save(commit=False)
        user.set_password(self.cleaned_data['password'])
        if commit:
            user.save()
        return user

class BannedRegistrationForm(BaseRegistrationForm, BannedDomainMixin):

    pass

class VerifyRegistrationForm(BaseRegistrationForm, VerifyPasswordMixin, VerifyEmailMixin):

    def __init__(self, *args, **kwargs):

        super(VerifyRegistrationForm, self).__init__(*args, **kwargs)
        del self.helper.layout.fields[-2]
        self.helper.layout.insert(-1,
            Row(
                Div(Field('email'), css_class='col-xs-6',),
                Div(Field('confirm_email'), css_class='col-xs-6',),
            ),
        )
        self.helper.layout.insert(-1,
            Row(
                Div(Field('password'), css_class='col-xs-6',),
                Div(Field('confirm_password'), css_class='col-xs-6',),
            ),
        )

class BannedVerifyRegistrationForm(VerifyRegistrationForm, BannedDomainMixin):

    pass

class RememberMeMixin(forms.Form):

    pass

class DualLoginForm(forms.Form):

    #254 is the max length of the forms.EmailField by default... since it can be
    #either field we use the max for this.

    #Note: since we aren't using a forms.EmailField + EmailValidator we lose the 
    #validation of emails here. This is handled by the clean method(s) with 
    #validate_email(email)

    username_email = forms.CharField(label=_("Username or Email"), max_length=254)
    password = forms.CharField(label=_("Password"), strip=False, widget=forms.PasswordInput)
    
    """ Redirection doesn't currently work, have to figure out how to
    layer dynamically passing the next field.... """
    
    redirect_field_name = 'next_url'
    next_url = forms.CharField(widget=forms.HiddenInput(),required=False)

    error_messages = {
        'invalid_login': _('Please enter a correct %(field)s and password. '
            "These fields are case-sensitive."),
        'inactive': _('This account is not currently active. ' 
            'If you believe this to be an error please contact our support.')
    }

    def __init__(self, request=None, *args, **kwargs):

        self.request = request
        self.email = False
        self.cache = None
        self.model = get_user_model()
        forms.Form.__init__(self, *args, **kwargs)
        self.helper = FormHelper(self)
        self.helper.layout = Layout(
            'next_url',
            Row(
                Div(
                    Field('username_email', placeholder="Username / Email"),
                    css_class='col-xs-12',
                ),
            ),
            Row(
                Div(
                    Field('password'),
                    css_class='col-xs-12',
                ),
            ),
        )
        #Submit form button
        self.helper.layout.append(
            Submit('Submit', 'submit', css_class='btn btn-default'),
        )

        self.form_name = "login-form"

    @property
    def username_field(self):

        return self.model.USERNAME_FIELD

    @property
    def user(self):

        return self.cache

    def get_verbose_name(self, field):

        return self.model._meta.get_field(field).verbose_name

    def try_email(self, email):

        try:
            #You may wonder why the sensitive from is __iexact??
            #Emails are by default case insensitive.
            User.objects.get(email__iexact=email)
        except ObjectDoesNotExist:
            raise forms.ValidationError(
                self.error_messages['invalid_login'],
                code='invalid_login',
                params={'field': self.get_verbose_name('email')},
            )

    """ WE DO **NOT** want to use get_object_or_404(model, **kwargs) here!!

    It would be a terrible UI to try to login and upon a simple error redirecting the
    user to a 404 page. Every. Single. Time. Instead, what you want is a form error,
    which we will raise by catching the exception of ObjectDoesNotExist -> error. """

    def try_username(self, username):

        try:
             User.objects.get(username=username)
        except ObjectDoesNotExist:
            raise forms.ValidationError(
                self.error_messages['invalid_login'],
                code='invalid_login',
                params={'field': self.get_verbose_name('username')},
            )

    def clean_username_email(self):

        field = self.cleaned_data['username_email']
        try:
            validate_email(field)
            self.email = True
            self.try_email(field)
        except forms.ValidationError:
            self.email = False
            self.try_username(field)
        return field

    def authenticate_user(self, username_email, password):

        if self.email:
            try:
                user = User.objects.get(email__iexact=username_email)
                if user.check_password(password):

                    """ When authenticate is called from django.contrib.auth
                    it sets an attribute on the user to denote which backend
                    the user was successfully authenticated with.

                    This attribute is then used by the "login" function in
                    django.contrib.login as well.

                    Login does the following -

                         request.session[BACKEND_SESSION_KEY] = user.backend 

                    Hence, we must manually set this attribute in order for
                    'login' to work properly since we are bypassing authenticate.

                    Alternatively, we could make a custom backend.... """

                    user.backend = 'django.contrib.auth.backends.ModelBackend'
                    return user
            except ObjectDoesNotExist:
                pass
        else:
            """ This is django's authenticate function from 
                django.contrib.auth   ->   authenticate """

            return authenticate(username=username_email, password=password)
        return None

    def clean(self):

        username_email = self.cleaned_data.get('username_email')
        password = self.cleaned_data.get('password')

        if all([username_email, password]):
            # auth_kwargs = self.get_auth_kwargs(username_email, password)

            #The authenticate function only takes username and password 
            #Hence, the username_email field is the username argument here.
            self.cache = self.authenticate_user(username_email, password)
            if self.cache is not None:
                # -> Another func. if extending further to sep. logic...
                if not self.cache.is_active:
                    raise forms.ValidationError(
                        self.error_messages['inactive'],
                        code='inactive',
                    )
            else:
                print("Cache is none", self.cache)
                if self.email:
                    field_verbose = self.get_verbose_name('email')
                else:
                    field_verbose = self.get_verbose_name(self.username_field)
                raise forms.ValidationError(
                    self.error_messages['invalid_login'],
                    code='invalid_login',
                    params={'field': field_verbose})

class DualInsensitiveLoginForm(DualLoginForm):

    """ We are simply overloading the inherited methods, and replacing the .get() 
    methods of trying to fetch the objects from the database with:

        (field_name) = *           --->        (field_name)__iexact = *

    Equivalence of COLLATE NOCASE (or respective method in other DBMS). """

    def __init__(self, *args, **kwargs):

        super(DualInsensitiveLoginForm, self).__init__(*args, **kwargs)
        #Previous message said fields are case-sensitive and this is an 
        #insensitive form.....
        self.error_messages['invalid_login'] = _('Please enter a correct %(field)s and password. '
            "These fields are case-insensitive.")

    def try_email(self, email):

        try:
            email = User.objects.get(email__iexact=email).email
        except ObjectDoesNotExist:
            raise forms.ValidationError(
                self.error_messages['invalid_login'],
                code='invalid_login',
                params={'field': self.get_verbose_name('email')}
            )
        return email

    def try_username(self, username):

        try:
            username = User.objects.get(username__iexact=username).username
        except ObjectDoesNotExist:
            raise forms.ValidationError(
                self.error_messages['invalid_login'],
                code='invalid_login',
                params={'field': self.get_verbose_name('password')},
            )
        return username