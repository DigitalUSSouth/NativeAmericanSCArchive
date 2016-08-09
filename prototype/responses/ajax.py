import abc
from django.http import JsonResponse

from .base import AbstractResponse

class AbstractAjaxMixin(AbstractResponse):

    @abc.abstractmethod
    def get_json_response(self):

        """ A dictionary should be returned here. 

        If you wish to arbitraily nest / extend the data returned 
        here, you can keep calling super() on the derivatives and
        .update() on the returned data to merge the dictionaries.

        We perform in all regions that call this method by unpacking
        the key / value pairs as keyword arguemnts + their values. """

        pass

    def return_response(self):

        return JsonResponse(**self.get_json_response())

class AjaxPostMixin(AbstractAjaxMixin):

    @abc.abstractmethod
    def post(self, request, *args, **kwargs):
        
        pass

class AjaxGetMixin(AbstractAjaxMixin):

    @abc.abstractmethod
    def get(self, request, *args, **kwargs):

        pass

class AjaxView(AjaxPostMixin, AjaxGetMixin):

    pass