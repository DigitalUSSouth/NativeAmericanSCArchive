#This is a FormView's metaclass.... this is needed in order
#to provide the metaclass delegate at the same level in order
#to prevent metaclass conflicts.
from django.views.generic.edit import FormMixinBase, FormView
from .forms import FormResponse, FormResponseDelegate

#For the AjaxView
from django.views.generic import View
from .ajax import AjaxMixin

#This is needed to resolve the metaclass conflict....
class FormResponseViewMeta(FormResponseDelegate, FormMixinBase):

    """ This is to avoid metaclass conflicts again.... """

    def __new__(meta, name, bases, dct):

        # print("FormResponseViewMeta __new__")
        FormMixinBase.__new__(meta, name, bases, dct)
        # print("FormMixinBase __new__")
        # print("super(FormResponseViewMeta, meta).__new__")
        return super(FormResponseViewMeta, meta).__new__(meta, name, bases, dct)

class FormResponseView(FormResponse, FormView, metaclass=FormResponseViewMeta):

    """ This is simply to provide an easier delegate.

    Everything you would normally pass a FormView to you now
    pass a FormResponseView to. This will include the entire
    response inheritance as well as the FormView methods /
    hierarchy merged together by the FormResponseViewMeta.

    This saves the user from having to continously type

        MyClass(FormView, metaclass=FormResponseViewMeta)

    every single time they want to use this with a FormView. """

    def __init__(self):

        super().__init__()

    def check_mixin_attributes(self):

        """ Calling super().check_mixin_attributes() is redundant
        here, we would want to depend on inheritance to check
        the full hierarchy from top -> down, but... this would
        just call BaseAbstractResponse's check_mixin_attributes
        method which just returns True. --> redundant. """

        missing = []
        if self.form_class is None:
            missing.append("form_class")
        if self.template_name is None:
            missing.append('template_name')
        if self.success_url is None:
            missing.append("success_url")
        if missing:
            raise AttributeError("All derivatives of FormResponseView " +
                "must have the\n following class variables set: " +
                ", ".join(missing) + "\nThese variables must not be " +
                "'None'.")

#Note, view does not have the post and get methods, etc. 
#The mro here won't overload the abstract methodss
#We use View here since we'd just have to use it anyway in 
#any class that uses this....
class AjaxView(AjaxMixin, View):

    pass