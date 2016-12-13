from django import template
import re

register = template.Library()

@register.filter(name='readable_name')
def readable_name(value):

	# if ord(value[0]) < ord('A'):
	value = value.capitalize()

	try:
		form_num = next(re.finditer(r'\d+$', value)).group(0)
		value = value[:value.rindex(form_num)]
	except StopIteration: #No form number...
		form_num = None

	if '_' in value:
		value = " ".join((x.capitalize() for x in value.split("_")))

	value += " Form"
	return value + " #%s" % form_num if form_num else value