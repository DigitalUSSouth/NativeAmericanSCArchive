from django.shortcuts import render
from django.http import HttpResponseRedirect
from django.core.urlresolvers import reverse

from django.contrib import messages

from django.forms.formsets import BaseFormSet
from formtools.wizard.views import SessionWizardView
from django.views.generic import ListView, DetailView

from django.forms.formsets import ManagementForm

from django.db.models import Prefetch

from django.utils import six
from django.utils.datastructures import SortedDict

from .models import *
from .forms import (
    DocumentForm1, DocumentForm2, DocumentForm3,
    AlternateTitleFormSet, RoleFormSet, 
    GeoMachineFormSet, GeoHumanFormSet, 
    ShelfMarkForm,
)

DOCUMENT_FORMS = [
    ('document', DocumentForm1),
    ('alternate_titles', AlternateTitleFormSet),
    ('document2', DocumentForm2),
    ('role', RoleFormSet),
    ('machine_location', GeoMachineFormSet),
    ('human_location', GeoHumanFormSet),
    ('shelf_mark', ShelfMarkForm),
    ('document3', DocumentForm3),
]

INSTANCE_DICT = {
    # 'alternate_titles': AlternativeTitle.objects.none(),
    # 'role': Role.objects.none(),
    # 'machine_location': GeographicLocationMachine.objects.none(),
    # 'human_location': GeographicLocationHuman.objects.none(),
}

def home(request):

    ctx = {'num_docs': Document.objects.all().count()}
    return render(request, "base.html", ctx)
    
class DocumentDirectory(ListView):

    model = Document
    context_object_name = 'documents'
    template_name = 'document/list.html'

class DocumentWizard(SessionWizardView):

    form_list = DOCUMENT_FORMS
    template_name = 'document/forms/wizard.html'

    def get_context_data(self, form, **kwargs):

        context = super().get_context_data(form, **kwargs)
        par_ref = '.form-group'
        helper = isinstance(form, BaseFormSet)
        context['par_ref'] = par_ref if not helper else '.parent'
        return context

    def post(self, *args, **kwargs):

        form = super().post(*args, **kwargs)
        print(form)
        return form

    def done(self, forms, form_dict, **kwargs):

        """ This doesn't even remotely handle the things it needs to.
        As one example, all blank formsets would actually hit the 
        database here... """

        m = messages.success(self.request, 'You have succesfully added a ' +
            'document')
        doc = form_dict['document'].save(commit=False)
        doc2_data = form_dict['document2'].cleaned_data
        doc3_data = form_dict['document3'].cleaned_data
        doc.description = doc2_data['description']
        doc.full_text = doc2_data['full_text']
        doc.usage_rights = doc3_data['usage_rights']
        doc.notes = doc3_data['notes']
        doc.save()

        formsets = ['alternate_titles', 'machine_location',
            'role', 'human_location']
        for formset in formsets:
            fset = form_dict[formset]
            for form in fset.forms:
                # instance = form.save(doc, commit=False)
                instance = form.save(commit=False)
                instance.document = doc
                instance.save()

        shelf_mark = form_dict['shelf_mark'].save(commit=False)
        shelf_mark.document = doc
        shelf_mark.save()
        return HttpResponseRedirect(reverse("document_detail",
            kwargs={'pk': doc.pk}))

class DocumentDetail(DetailView):

    model = Document
    context_object_name = 'document'
    template_name = 'document/detail/main.html'

    """ 1 additional query for each prefetched relationship:
        -> 7 queries = 6 prefetches + 1 for object -> pk """

    def get_queryset(self):

        return super().get_queryset().\
            prefetch_related(
                "role_set",
                "alternativetitle_set",
                "shelfmark_set", 
                "geographiclocationmachine_set",
                "geographiclocationhuman_set",
                "contributinginstitution_set")

    def get_context_data(self, **kwargs):

        context = super().get_context_data(**kwargs)
        return context
