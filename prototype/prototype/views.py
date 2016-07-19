from django.shortcuts import render
from django.core.urlresolvers import reverse

from formtools.wizard.views import SessionWizardView
from django.views.generic import ListView, DetailView

from .models import Document
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
	('shelfmark', ShelfMarkForm),
	('document3', DocumentForm3),
]

def home(request):

	return render(request, "base.html", {})
	
class DocumentDirectory(ListView):

	model = Document
	context_object_name = 'documents'
	template_name = 'document/list.html'

class DocumentWizard(SessionWizardView):

	form_list = DOCUMENT_FORMS
	template_name = 'document/forms/wizard.html'

	def done(self, forms, form_dict, **kwargs):

		""" This doesn't even remotely handle the things it needs to.
		As one example, all blank formsets would actually hit the 
		database here... """

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
				instance = form.save(commit=False)
				instance.document = doc
				instance.save()

		shelf_mark = form_dict['shelfmark'].save(commit=False)
		shelf_mark.document = doc
		shelf_mark.save()

class DocumentDetail(DetailView):

	model = Document
	context_object_name = 'document'
	template_name = 'document/detail/main.html'

	def get_context_data(self, **kwargs):

		context = super().get_context_data(**kwargs)
		print(dir(context['object']))
		return context
