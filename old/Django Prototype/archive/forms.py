#core Django imports
from django import forms

#3rd-Party packages
from crispy_forms.helper import FormHelper
from crispy_forms.layout import Layout, Field, Div, Row, Submit

#Local imports
from .models import *

class UploadEntryForm(forms.Form):

	_file = forms.FileField(label="Upload a File",)

	def __init__(self, *args, **kwargs):

		super().__init__(*args, **kwargs)
		self.helper = FormHelper(self)
		self.helper.layout = Layout(
			Row(
				Div(Field('_file', css_class='file-input'),
					css_class='col-xs-12',
				),
			),
			Submit('save', 'Submit Entry'),
		)

	def clean__file(self,):

		pass