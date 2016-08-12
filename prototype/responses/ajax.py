import abc
from django.http import JsonResponse
from django.views.generic import View

from .base import AbstractResponseMixin, BaseAbstractResponse


""" You'd think this should inherit from BaseAbstractResponse, so 
that all further subclasses in the hierarchy directly extend it.

However, for using the mixins below with FormResponse in
forms.py and with things such as FormView, which uses 
BaseFormMixin metaclass will cause a metaclass conflict.... """

class BaseAbstractAjaxMixin(metaclass=abc.ABCMeta):

    def __init__(self):

        super().__init__()
        # print("In BaseAbstractAjaxMixin __init__")

    @abc.abstractmethod
    def get_json(self):

        """ A dictionary should be returned here. 

        If you wish to arbitraily nest / extend the data returned 
        here, you can keep calling super() on the derivatives and
        .update() on the returned data to merge the dictionaries.

        We perform in all regions that call this method by unpacking
        the key / value pairs as keyword arguemnts + their values. """

    def return_response(self):

        if self.request.is_ajax():
            return JsonResponse(**self.get_json())
        else:
            return self.get_default_response()

class AbstractAjaxMixin(BaseAbstractAjaxMixin, BaseAbstractResponse):

    """ Note the order here.... BaseAbstractAjaxMixin MUST be
    the first in the hierarchy as it EXTENDS the functionality of
    BaseAbstractResponse. Otherwise the abstract method would
    overload the return_response in BaseAbstractAjaxMixin, 
    defeating the purpose of the extension and inevitably
    raising an error since you just did something really really
    dumb and now you don't have the real return_response method.
    """

class AjaxGetMixin(AbstractAjaxMixin):

    def __init__(self):

        super().__init__()
        # print("In AjaxGetMixin __init__")

    @abc.abstractmethod
    def get(self, request, *args, **kwargs):

        """ This is to provide an abstract base method so 
        that every derivative of this class must override the
        get method, since the purpose of using this is to 
        provide an ajax get method..... """

class AjaxPostMixin(AbstractAjaxMixin):

    def __init__(self):

        super().__init__()
        # print("In AjaxPostMixin __init__")

    @abc.abstractmethod
    def post(self, request, *args, **kwargs):
        
        """ This is to provide an abstract base method so 
        that every derivative of this class must override the
        post method, since the purpose of using this is to 
        provide an ajax post method..... """

class AjaxMixin(AjaxGetMixin, AjaxPostMixin):

    def __init__(self):

        super().__init__()
        # print("In AjaxMixin __init__")

    """ AjaxMixin merges poth AjaxPostMixin and AjaxGetMixin, and
    thus it has both abstractmethods post and get. So, this
    creates a fully abstract ajax view with both methods.

    We could extend this further for PUT, DELETE, HEAD, etc...
    But, these aren't needed.... """