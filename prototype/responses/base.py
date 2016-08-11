import abc
from django.http import HttpResponseRedirect

class ResponseMixin(type):

    def __call__(cls, *args, **kwargs):

        _object = type.__call__(cls, *args, **kwargs)
        _object.check_mixin_attributes(cls)
        return _object

class AbstractResponseMixin(ResponseMixin, abc.ABCMeta):

    """ This is simply to merge both functionality of the ResponseMixin,
    and the ABCMeta metaclasses, they must both be contained in the
    same base level. Otherwise, trying to use these seperately will
    raise an error. """

class BaseResponse:

     """ We want EVERY connection subclassed from this to 
    be encrypted with SSL / secure. 

    If you want every connection regardless if this is in
    the mro, then you should create a middleware to handle
    all requests through it. """

     def dispatch(self, request, *args, **kwargs):

        # if not request.is_secure():
        #     return HttpResponseRedirect(
        #         request.build_absolute_uri(
        #             request.get_full_path()
        #         ).\
        #         replace("http", "https")
        #     )
        print("Called base response dispatch")
        return super(self.__class__, self).dispatch(request, *args, **kwargs)

class BaseAbstractResponse(BaseResponse, metaclass=AbstractResponseMixin):

    @abc.abstractmethod
    def check_mixin_attributes(self):

        """ In every derivative of this that uses a new mixin
        from django.contrib.auth.mixins, this method should be 
        overloaded...

                        *** NOT OVERRIDEN *** 

            We are depending on the inherited calls to check the
            dependent mixins / attributes that are set before
            the next level in the hierarchy. 

        ...in order to check that every attribute is set properly 
        for the mixins to work. 

        By default most of the mixins will work without checking, 
        this is simply to enforce our own specifications.

        e.g. - Let's say for all derivatives we want to ensure that
        raise_exceptions is True for mixins from django.contrib.auth. """

        return True #Simplest answer, no mixins = no missing attrs.

    @abc.abstractmethod
    def return_response(self):

        pass

class AbstractResponse(BaseAbstractResponse):

    #Yes.... this is overly verbose, but there are a ton of use cases
    #where you may want to do more than return the response. It is by
    #fair easier to overload a single function and keep `return_response`
    #in tact by splicing both logical routes...
    @abc.abstractmethod
    def get_default_response(self, response):

        return super(AbstractResponse, self).\
               render_to_response(self.get_context_data())

    def return_response(self):

        return self.get_default_response()