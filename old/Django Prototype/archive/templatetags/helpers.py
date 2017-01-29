from django import template

register = template.Library()

@register.simple_tag
def modulus(a, n):

	"""

	From abstract algebra, we know that the modulus is
	defined as follows:

	For a, b in Z_n, a = b(mod n) iff a - b = nk for some k in Z.

	e.g. - In Z_4 we have that:

			4 = 0 (mod 4), because 4 - 0 = 4(1)
			4 = 1 (mod 3), because 4 - 1 = 3(1)

	The modulus operator is %. 

	Thus, for a = 4, n = 3 --> 4 % 3 = 1

	"""

	return a % n

@register.filter
def modulus_equals(a, n, val):

	""" This is used as a filter if we want to check for specific
	modulus properties.

	Example. We want to know if 4 mod 3 == 1 in our template. """

	return a % n == val

@register.filter
def minus(value, new):

	return int(value) - new

@register.filter
def startswith(string, value):

	return string.startswith(value)

@register.filter
def endswith(string, value):

	return string.endswith(value)