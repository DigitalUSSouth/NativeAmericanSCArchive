from django.core.exceptions import PermissionDenied

from django.shortcuts import render
from django.views.generic import ListView, DetailView, UpdateView

from django.db.models import Prefetch

from .forms import UploadEntryForm
from .models import Entry

from responses.views import FormResponseView
#This is needed for ArchiveDirectory's searching
#functionality.
from django.core.serializers import serialize
from responses.ajax import AjaxGetMixin

from search_utils import search_response
from utilities import PrefetchMixin

class ArchiveDirectory(AjaxGetMixin, ListView):

    model = Entry
    template_name = 'archive/directory/main.html'
    context_object_name = 'entries'

    def check_mixin_attributes(self):

        #This is BaseResponse's check_mixin_attributes which 
        #returns True by default since any user should be able to
        #filter the directory...
        return super().check_mixin_attributes()

    #We have to filter both of these regardless of the method...
    #So, we're decorating both to save code / not violating DRY.
    @search_response
    def get_json(self):

        data = serialize("json", self.object_list)
        return {'objects': serialize("json", self.object_list)}

    @search_response
    def get_default_response(self):

        #From AjaxGetMixin this method is inherited
        #from BaseAbstractAjaxMixin which defines the
        #get_default_resposne which defaults to 
        #self.render_to_respons(self.get_context_data())

        #Hence, this will return the normal view ....
        return super().get_default_response()

    def get(self, request, *args, **kwargs):

        return self.return_response()

class ArchiveTimeline(ListView):

    model = Entry
    context_object_name = "entries"
    template_name = 'archive/timeline/main.html'

class ArchiveEntryDetail(PrefetchMixin, DetailView):

    #This is the attribute used by PrefetchMixin to 
    #dynamically set the prefetch_related for all of these
    #reverse relationships that have File as a ForeignKey

    #You can arbitrarily add more here of course should these
    #reverse relationships be extended of course...
    prefetches =  [
         'role_set', 'contributinginstitution_set',
         'geographiclocation_set', 'digitaltype_set', 
         'lcsubjectheading_set', 'language_set',
    ]
    model = Entry
    template_name = "archive/detail/main.html"

#By default this isn't ajax, but let's say you wanted to put a
#a link in a navbar or something that pops up the simple submit
#file upload button you could then submit this have an ajax call
#hit the post of this view and everything would auto-magically
#work. Pretty flexible.

#If you DON'T ever need ajax with a view like this,
#simply subclass FormView. The entire point of
#FormResponseView is to handle BOTH.

class SubmitArchiveEntry(FormResponseView):

    form_class = UploadEntryForm
    template_name = 'archive/submit.html'
    success_url = "/archive/recent/"

    def __init__(self):

        """ This is in order to call the entire 'responses' 
        folder hierarchy that is derived from FormResponseView:

        Note: Right now this does absolutely nothing, because 
        there's nothing happening in any of the __init__'s but 
        this is obviously subject to change later. 

        So, may as well implement the functionality.... """

        super().__init__()

    """ This is the abstract method defined on FormResponsView
    in order to determine the success message.

    Note: This is for BOTH ajax and non-ajax requests. 

    If ajax it will add it to the json response, otherwise
    it will add use django.contrib.messages and the message
    will displayed on the redirect. """
    
    def get_success_msg(self):

        return "You have successfully submitted a new archive entry."

    def check_mixin_attributes(self):

        return super().check_mixin_attributes()

    def post(self, request):

        form = self.get_form(self.get_form_class())
        if form.is_valid():
            return self.form_valid(form)
        return self.form_invalid(form)

    def get(self, request):

        #This is to keep somebody from accidentally sending the
        #post data as a get request....  -_-
        if self.ajax:
            raise PermissionDenied
        return self.render_to_response(self.get_context_data())