import abc
from .base import BaseAbstractResponse, AbstractResponseMixin
from .ajax import AbstractAjaxMixin, AjaxMixin

class FormResponseMixin(type):

    #THIS HAS TO BE __CALL__ IT CANNOT BE __NEW__ OR __INIT__
    def __call__(cls, *args, **kwargs):

        _object = type.__call__(cls, *args, **kwargs)
        _object.check_valid_base()
        return _object

class FormResponseDelegate(AbstractResponseMixin, FormResponseMixin):

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

class FormResponse(AjaxMixin, metaclass=FormResponseDelegate):

    """ This is to handle both ajax and non-ajax request calls 
        for forms.... """

    def check_valid_base(self):

        base_names = [x.__name__ for x in self.__class__.__mro__]
        
        import pprint
        #FormMixinBase is the metaclass that is called by 
        #FormView
        if 'FormMixinBase' not in base_names:
            raise UserWarning("FormResponseMixin must be used with " +
                "a class that derives from FormView located within " +
                "django.views.generic.")

    def get_json_response(self, form):

        #self.get_form() is a method of django.generic.views.FormView
        #class. This will be available since by check_valid_base all
        #derivatives of this class must have this base parent ==> 
        #having this method.
        print("GET_JSON_RESPONSE FOR FORMRESPONSE CALLED")
        return {'data': self.get_form().errors, 'status': 400}

    def get_default_response(self):

        return self.render_to_response(self.get_context_data())

    def form_invalid(self, form):

        print("FORM INVALID FOR FORMRESPONSE CALLED")
        response = super(self.__class__, self).form_invalid(form)
        self.return_response()

    def form_valid(self, form):

        print("FORM VALID FOR FORMRESPONSE CALLED")
        response = super(self.__class__, self).form_valid(form)
        self.return_response()