import abc
from .ajax import AbstractAjaxMixin
from .base import AbstractResponse

class FormResponseMixin(type):

    def __call__(cls, *args, **kwargs):

        _object = type.__call__(cls, *args, **kwargs)
        _object.check_valid_base()
        return _object

""" This mixin will handle both ajax and non-ajax request calls 
for forms.... """

class FormResponse(AbstractAjaxMixin, FormResponseMixin):

    # def check_mixin_attributes(self):

        # return super(FormResponse, self).check_mixin_attributes()
        
    def check_valid_base(self):

        print("Form response mixin bases", self.__class__.__mro__)
        if 'FormView' not in self.__class__.__mro__:
            raise UserWarning("FormResponseMixin must be used with " +
                "a class that derives from FormView located within " +
                "django.views.generic.")

    def get_json_response(self, form):

        #self.get_form() is a method of django.generic.views.FormView
        #class. This will be available since by check_valid_base all
        #derivatives of this class must have this base parent ==> 
        #having this method.

        return {'data': self.get_form().errors, 'status': 400}

    def get_default_response(self):

        return super(ResponseMixin, self).get_default_response()

    def form_invalid(self, form):

        response = super(FormResponseMixin, self).form_invalid(form)
        self.return_response()

    def form_valid(self, form):

        response = super(FormResponseMixin, self).form_valid(form)
        self.return_response()

#Just use the NASCA Mixin....
# class LockedAccessFormResponse(FormResponse):

    # pass