from django import forms

from crispy_forms.helper import FormHelper
from crispy_forms.layout import (Layout, Field, Div, Row, Submit,)

class ContactForm(forms.Form):

	subject = forms.CharField(max_length=60)
	body = forms.CharField(max_length=2500, widget=forms.Textarea)

	def __init__(self, *args, **kwargs):

		super().__init__(*args, **kwargs)
		self.helper = FormHelper(self)
		self.helper.form_tag = False
		self.helper.layout = Layout(
			Row(
				Div(Field('subject'), css_class='col-xs-12'),
			),
			Row(
				Div(Field('body'), css_class='col-xs-12'),
			),
		)