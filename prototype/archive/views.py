from django.shortcuts import render
from django.db import models

from django.views.generic import (View, FormView, ListView, 
    DetailView, UpdateView
)

#This is a FormView's metaclass....
from django.views.generic.edit import FormMixinBase

from .forms import UploadEntryForm
from responses import FormResponse
from responses.ajax import AbstractAjaxMixin

import abc, inspect

class ArchiveDirectory(ListView):

    pass

class ArchiveEntryDetail(DetailView):

    pass

def inject_wrapped_method(method):
    print("Injecting method....", method)
    def wrapped(*args, **kwargs):
        return method(*args, **kwargs)
    return wrapped

#This is needed to resolve the metaclass conflict....
class Base(FormMixinBase, FormResponse):

    def __init__(cls, name, bases, attrs):

        FormResponse.__init__(cls)

    # def mro(cls):

        # return cls.__mro__

    def __new__(meta, name, bases, namespace):

        #By default the ABCMeta class only checks if the 
        #abstract methods are overriden on INSTANTIATION,
        #but class based views are never initialized, we 
        #simply call the as_view method. Thus, before even
        #being to create subclasses of these view using
        #anything from responses we override the __new__
        #methods to check the class namespace against the
        #abstract methods.
        amethods = meta.__abstractmethods__
        to_be_wrapped = ['mro', '']
        print("AMETHODS", amethods)

        # List of namespace keys from FormResponse
        # This is the ENTIRE namespace.
        inherited_functions = dir(meta)

        missing = [x for x in amethods if x not in namespace]
        if missing:
            raise TypeError("Can't insantiate abstract class " +
                "%s with abstract methods %s." % 
                (name, ", ".join(missing)))
        c1 = FormMixinBase.__new__(meta, name, bases, namespace)
        bases = tuple(c1.mro())
        name = c1.__name__
        dct = c1.__dict__.copy()
        namespace.update(dct)
        for item in inherited_functions:
            attribute = getattr(FormResponse, item)

            # We are iterating through the ENTIRE
            # list of ALL inherited methods in FormRespone

            #Thus, some of these will be the abstract methods inherited
            #from higher subclasses. We DO NOT want to replace the
            #namespace with these as this is completely redundant
            #and just all around dumb.
            print("item", item)
            if item == "mro":
                namespace[item] = lambda cls=meta: cls.__mro__
            if inspect.isfunction(attribute) and not item in amethods:
                #We want to wrap the function....
                if item in namespace:
                    namespace[item] = inject_wrapped_method(attribute)
                else:
                    namespace[item] = attribute
            elif item == '__mro__':
                #FormResponse is first in the hierarchy....
                namespace['__mro__'] = attribute + c1.__mro__
            elif item.startswith("_abc"):
                namespace[item] = attribute
        return type.__new__(meta, name, bases, namespace)

class SubmitArchiveEntry(FormView, metaclass=Base):

    form_class = UploadEntryForm
    template_name = 'submit_file.html'
    success_url = "archive/submit-entry"

    def check_mixin_attributes(self):

        #If you want to call super() here, to call
        #BaseAbstractResponse's check_mixin_attributes
        #if you're not using any mixins in the future
        #you must call self.check_mixin_attributes(self.__class__)

        return self.check_mixin_attributes(self.__class__)

    def post(self, request):

        form = self.form_class(request.POST)
        if form.is_valid():
            return self.form_valid(form)
        return self.form_invalid(form)

    def get(self, request):

        print("CALLED SubmitArchiveEntry get")
        # YOU MUST SPECIFY THE CLASSES FOR SUPER HERE....
        # Otherwise... it will result in infinite recursion!!!!!
        return self.render_to_response(self.get_context_data())