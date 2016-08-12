from django.shortcuts import render
from django.views.generic import ListView, DetailView, UpdateView

from .forms import UploadEntryForm
from responses.views import FormResponseView

class ArchiveDirectory(ListView):

    pass

class ArchiveEntryDetail(DetailView):

    pass

class SubmitArchiveEntry(FormResponseView):

    form_class = UploadEntryForm
    template_name = 'submit_file.html'
    success_url = "."

    def __init__(self):

        # print("SubmitArchiveEntry __init__")
        super().__init__()

    def get_success_json(self):

        return {'data': 'sdfsdfds'}

    def check_mixin_attributes(self):

        #If you want to call super() here, to call
        #BaseAbstractResponse's check_mixin_attributes
        #if you're not using any mixins in the future
        #you must call self.check_mixin_attributes(self.__class__)

        return super().check_mixin_attributes()

    def post(self, request):

        form = self.get_form(self.get_form_class())
        if form.is_valid():
            return self.form_valid(form)
        return self.form_invalid(form)

    def get(self, request):

        return self.render_to_response(self.get_context_data())