from django import forms

from .models import *

from crispy_forms.helper import FormHelper
from crispy_forms.layout import (Layout, Field, Div, Row, 
	Submit, Button, Column, HTML)

class UploadFileForm(forms.Form):

	_file = forms.FileField(label="Upload a File",)

	def __init__(self, *args, **kwargs):

		super().__init__(*args, **kwargs)
		self.helper = FormHelper(self)
		self.helper.layout = Layout(
			Row(
				Div(Field('_file'),
					css_class='col-xs-12',
				),
			),
			Submit('save', 'save'),
		)

	def clean__file(self,):

		pass