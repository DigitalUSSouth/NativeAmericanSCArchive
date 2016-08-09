import abc

# from django.views.generic import View
from django.http import HttpResponseRedirect, JsonResponse

class ResponseMixin(type):

    def __call__(cls, *args, **kwargs):

        print("ARGS", args, "KWARGS", kwargs)
        _object = type.__call__(cls, *args, **kwargs)
        _object.check_mixin_attributes()
        return _object

class AbstractResponseMixin(ResponseMixin, abc.ABCMeta):

    """ This is simply to merge both functionality of the ResponseMixin,
    and the ABCMeta metaclasses, they must both be contained in the
    same base level. Otherwise, trying to use these seperately will
    raise an error. """

    pass

""" REMOVED VIEW PARENT HERE .... """

class AbstractResponse(metaclass=AbstractResponseMixin):

    """ We want EVERY connection be encrypted with SSL / secure. """
    
    def dispatch(self, request, *args, **kwrgs):

        if not request.is_secure():
            return HttpResponseRedirect(
                request.build_absolute_url(
                    request.get_full_path()
                ).\
                replace("http", "https")
            )
        return super(AbstractResponse, self).dispatch(request, *args, **kwargs)


    @abc.abstractmethod
    def check_mixin_attributes(self):

        """ In every derivative of this that uses a new mixin
        from django.contrib.auth.mixins, this method should be 
        overloaded 

                        *** NOT OVERRIDEN *** 

            We are depending on the inherited calls to check the
            dependent mixins / attributes that are set before
            the next level in the hierarchy. 

        in orfer to check that every attribute is set properly for
        the mixins to work. """

        return True #Simplest answer, no mixins = no missing attrs.

    #Yes.... this is overly verbose, but there are a ton of use cases
    #where you may want to do more than return the response. It is by
    #fair easier to overload a single function and keep `return_response`
    #in tact by splicing both logical routes...
    @abc.abstractmethod
    def get_default_response(self, response):

        return super(AbstractResponse, self).\
               render_to_response(self.get_context_data())

    @abc.abstractmethod
    def return_response(self):

        return self.get_default_response()