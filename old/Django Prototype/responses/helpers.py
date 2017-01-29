from functools import wraps

""" Currently not being used, but may be useful in the future in
case anyone ever needs to inject a method to resolve mro 
complications. """

def inject_wrapped_method(method):
    print("Injecting method....", method)
    @wraps(method)
    def wrapped(self, *args, **kwargs):
        _super = super(self.__class__, self)
        response = method(self, *args, **kwargs)
        print("\nResponse for method", method, response, "\n")
        return response
    return wrapped