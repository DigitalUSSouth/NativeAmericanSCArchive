from django import forms

from .models import *

from crispy_forms.helper import FormHelper
from crispy_forms.layout import (Layout, Field, Div, Row, 
	Submit, Button, Column, HTML)
from crispy_forms.bootstrap import (FormActions, PrependedText,
	PrependedAppendedText, FieldWithButtons, StrictButton)

from django.forms.models import inlineformset_factory

class RoleForm(forms.ModelForm):

	class Meta:

		model = Role
		fields = ('role',)

	def __init__(self, *args, **kwargs):

		super().__init__(*args, **kwargs)
		self.helper = FormHelper(self)
		self.helper.form_tags = False
		self.helper.layout = Layout(
			Row(Div(Field('role'), css_class='col-xs-6'),),
		)

class GeoMachineForm(forms.ModelForm):

	class Meta:

		model = GeographicLocationMachine
		fields = ('latitude', 'longitude')

	def __init__(self, *args, **kwargs):

		super().__init__(*args, **kwargs)
		self.helper = FormHelper(self)
		self.helper.form_tags = False
		self.helper.layout = Layout(
			Row(
				Div(Field('latitude'), css_class='col-xs-6'),
				Div(Field('longitude'), css_class='col-xs-6')
			),
			Row(
				Div(
					HTML("<button class='btn btn-danger delete pull-right'>" +
							"<i class='fa fa-close'></i>&nbsp;Delete" +
						 "</button>"),
					css_class='col-xs-12'
				),
			),
		)

class GeoHumanForm(forms.ModelForm):

	class Meta:

		model = GeographicLocationHuman
		fields = ('city', 'country', 'state', 'zip_code', 'country',)

	# def clean_zip_code(self):

	# 	zip_code = self.cleaned_data['zip_code']
	# 	if zip_code not in constants.ZIP_CODES:
	# 		raise forms.ValidationError("The zip code you entered is not " +
	# 			"valid. Please try again.")
	# 	return zip_code

# VALIDATE_MAX VALIDATES THE MAX_NUM argument, similarly VALIDATE_MIN

AlternateTitleFormSet = inlineformset_factory(Document, 
	AlternativeTitle, fields=('alternative_title',),
	extra=0, min_num=1, max_num=5, validate_max=True,
	validate_min=True)

RoleFormSet = inlineformset_factory(Document, Role, 
	form=RoleForm, extra=0,
	min_num=1, max_num=5,
	validate_min=True, validate_max=True,)

GeoMachineFormSet = inlineformset_factory(Document, 
	GeographicLocationMachine, form=GeoMachineForm,
	extra=0, min_num=1, validate_min=True,
)
GeoHumanFormSet = inlineformset_factory(Document,
	GeographicLocationHuman, form=GeoHumanForm, #form is implemented for possible zip clean.
	# fields=('city', 'county', 'state', 'zip_code', 'country',),
	extra=0, min_num=1, max_num=5,
	validate_min=True, validate_max=True
)

class DocumentForm1(forms.ModelForm):

	class Meta:

		model = Document
		fields = ('archive', 'title', 'language', 'file_format',
			'url', 'thumbnail_url',
			'date', 'date_human', 
			'date_digital', 'date_digital_human',)

	def __init__(self, *args, **kwargs):

		super().__init__(*args, **kwargs)
		self.helper = FormHelper(self)
		self.helper.form_tags = False
		self.helper.layout = Layout(
			Row(
				Div(Field('archive'), css_class='col-xs-12'),
			),
			Row(
				Div(Field('title'), css_class='col-xs-12'),
			),
			Row(
				Div(Field('url'), css_class='col-xs-6'),
				Div(Field('thumbnail_url'), css_class='col-xs-6'),
			),
			Row(
				Div(Field('date'), css_class='col-xs-6'),
				Div(Field('date_human'), css_class='col-xs-6'),
			),
			Row(
				Div(Field('date_digital'), css_class='col-xs-6'),
				Div(Field('date_digital_human'), css_class='col-xs-6'),
			),
			Row(
				Div(Field('language'),css_class='col-xs-6'),
				Div(Field('file_format'),css_class='col-xs-6'),
			),
		)

class DocumentForm2(forms.ModelForm):

	class Meta:

		model = Document
		fields = ('description', 'full_text')

class DocumentForm3(forms.ModelForm):

	class Meta:

		model = Document
		fields = ('usage_rights', 'notes')

class ShelfMarkForm(forms.ModelForm):

	class Meta:

		model = ShelfMark
		fields = ('collection_level', 'box_level', 'series_level', 
			'folder_level',)

	def __init__(self, *args, **kwargs):

		super().__init__(*args, **kwargs)
		self.helper = FormHelper(self)
		self.helper.layout = Layout(
			Row(
				Div(Field('collection_level'), css_class='col-xs-6'),
				Div(Field('box_level'), css_class='col-xs-6'),
			),
			Row(
				Div(Field('series_level'), css_class='col-xs-6'),
				Div(Field('folder_level'), css_class='col-xs-6'),
			),
		)