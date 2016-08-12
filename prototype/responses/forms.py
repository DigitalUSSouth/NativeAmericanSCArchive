import abc, inspect #For continued abstractmethods + metaclass

from .base import AbstractResponseMixin
from .ajax import AjaxMixin

from .helpers import inject_wrapped_method

class FormResponseMixin(type):

    """ This is a delegate in order to put all of the currently
    called metaclasses in the hierarchy into a single wrapper.
    This must be done otherwise you would have a metaclass
    conflict error. 

    The metaclass conflict error occurs, because the metaclass
    of a derived class must be a (non-strict) subclass of the
    metaclasses of all its bases.

    Hence, the metaclass of FormResponse is a subclass of all
    of the metaclasses of all classes in the hierarchy.

    Thus, the error is avoided. Should you wish to see this
    error simply change 'metaclass=*' in FormResponse to
    be metaclass=FormResponseMixin. """

    def __call__(cls, *args, **kwargs):

        # print("Called form response mixin")
        _object = type.__call__(cls, *args, **kwargs)
        _object.check_valid_base()
        return _object

class FormResponseDelegate(FormResponseMixin, AbstractResponseMixin):

    def __call__(self):

        #We have two methods that both use __call__
        #The ResponseMixin that is a metaclass to AbstractResponseMixin.
        #and FormResponseMixin, we must call both of these here for 
        #full delegation through the hierarchy of metaclasses / mixins.
        c1 = AbstractResponseMixin.__call__(self)
        # print("FormResponseDelegate c1 call")
        c2 = FormResponseMixin.__call__(self)
        # print("FormResponseDelegate c2 call")
        return c1

class FormResponse(AjaxMixin, metaclass=FormResponseDelegate):

    """ This is to handle both ajax and non-ajax request calls 
        for forms.... """

    def __init__(self, *args, **kwargs):

        # print("Called form response")
        super().__init__()
        self._response = None
        self._valid = False

    @property
    def valid(self):

        return self._valid

    @valid.setter
    def valid(self, boolean):

        #Everything in Python has a "truthiness", but yeah no.
        #We're not allowing any value that equates to some random
        #bool.

        if not (boolean is True or boolean is False):
            raise ValueError("The valid property of FormResponse " +
                "must be set to either 'True' or 'False'.")
        self._valid = boolean

    @property
    def response(self):

        return self._response

    @response.setter
    def response(self, new_response):

        self._response = new_response

    def check_valid_base(self):

        bases = [cls.__name__ for cls in self.__class__.__mro__]
        if 'FormView' not in bases:
            raise UserWarning("FormResponse must be used with " +
                "a class that derives from FormView located within " +
                "django.views.generic.")

    @abc.abstractmethod
    def get_success_json(self):

        """ Every form is different, it provides a different
        functionality and purpose. Because of this, what every
        form that uses the FormResponse Mixin will be returning
        upon successful form submissions is different.

        Hence, every subclass will need to override this abstract
        method in order to ensure the correct functionality for
        successful ajax posts. """ 

    def get_json(self, form):

        #self.get_form() = method of django.generic.views.FormView
        #class. Available since by check_valid_base all
        #derivatives of this class must have this base parent ==> 
        #having this method.
        if not self.valid:
            return {'data': self.get_form().errors, 'status': 400}
        else:
            #301 is the status of HttpResponseRedirect.

            #We will detect this display a time for redirection
            #and then change the location through jquery.
            return {'data': {'msg': self.get_success_json()}, 
                    'status': 301}

    def get_default_response(self):

        return self.response

    def form_invalid(self, form):

        self.valid = False
        self.response = super().form_invalid(form)
        return self.return_response()

    def form_valid(self, form):

        self.valid = True
        self.response = super().form_valid(form)
        return self.return_response()

    def return_response(self):

        if self.request.is_ajax():
            return JsonResponse(**self.get_json())
        else:
            return self.response