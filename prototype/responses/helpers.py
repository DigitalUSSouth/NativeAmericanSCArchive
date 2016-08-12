from functools import wraps

def inject_wrapped_method(method):
    print("Injecting method....", method)
    @wraps(method)
    def wrapped(self, *args, **kwargs):
        _super = super(self.__class__, self)
        response = method(self, *args, **kwargs)
        print("\nResponse for method", method, response, "\n")
        return response
    return wrapped