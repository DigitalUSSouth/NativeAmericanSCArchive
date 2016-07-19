from django.db import models
from django.conf import settings

from .constants import (CONTENT_TYPE_CHOICES, ARCHIVES, ROLES,
	INSTITUTIONS, FILE_FORMATS, ZIP_CODES)

class Document(models.Model):

	created = models.DateTimeField(auto_now_add=True)
	modified = models.DateTimeField(auto_now_add=True)

	archive = models.CharField(blank=False, null=False, choices=ARCHIVES,
		max_length=200, 
		help_text='Enter the archive this document belongs to. e.g. - Simms')
	url = models.URLField(blank=False, null=False, max_length=200,
		help_text='This URL denotes a unique URI for this document.')
	title = models.CharField(blank=False, null=False, max_length=100,
		help_text='Enter the name for this document.')
	date = models.DateTimeField(null=False, blank=False,
		help_text='Specify the date(s) of the original artifact. ' +
		'This may be a range in ISO (date) format.',
		verbose_name='Original Artifact Date')
	date_human = models.CharField(blank=True, null=False, max_length=60,
		help_text='You may enter a human readable date. e.g - 20th Century',
		verbose_name='Human Original Artifact Date')
	date_digital = models.DateTimeField(null=False, blank=False,
		help_text='Specify the date(s) of the digital surrogate in ISO format.',
		verbose_name='Digital Date')
	date_digital_human = models.CharField(blank=True, null=False,
		max_length=80, verbose_name='Human Digital Date')

	copyright_holder = models.CharField(blank=False, null=False,
		max_length=100, verbose_name='Copyright Holder')
	language = models.CharField(blank=False, null=False,
		choices=settings.LANGUAGES, max_length=10)

	thumbnail_url = models.URLField(blank=True, null=False,
		max_length=100, verbose_name='Thumbnail URL')
	description = models.TextField(blank=True, null=False,
		max_length=2500)
	#Should probs be binary, but whatevs for now \_O_/
	full_text = models.TextField(max_length=50000, verbose_name='Full Text',
		help_text='You may enter the full text for this document in ' +
		'order to allow (OCR) support.')

	usage_rights = models.TextField(max_length=10000)
	file_format = models.CharField(blank=False, null=False,
		choices=FILE_FORMATS, max_length=10, verbose_name='File Format')
	notes = models.TextField(blank=True, null=False, max_length=500,
		help_text='Add any additional notes that are pertinent to this ' +
		'document.')

	def __str__(self):

		return "%s: %s" % (self.title, self.url)

class Role(models.Model):

	role = models.CharField(blank=False, null=False, choices=ROLES,
		max_length=30, help_text='Enter the role(s) you\'ve played in the creation ' +
		'of the document you are uploading.')
	document = models.ForeignKey(Document)

	@property
	def formatted_role_metadata(self):

		return "role_%s" % self.role

	def __str__(self):

		return self.role

class GeographicLocationMachine(models.Model):

	longitude = models.DecimalField(max_digits=9, decimal_places=6,
		help_text='Enter the longitude coordinates of this item in signed degrees.' +
		'You may specify a maximum of nine digits and six decimal places.')
	latitude = models.DecimalField(max_digits=9, decimal_places=6,
		help_text='Enter the latitude coordinates of this item in signed degrees.' +
		'You may specify a maximum of nine digits and six decimal places.')
	document = models.ForeignKey(Document)

	def __str__(self):

		return "(%.6f, %.6f)" % (self.longitude, self.latitude)

class GeographicLocationHuman(models.Model):

	city = models.CharField(max_length=50, blank=True, null=False)
	county = models.CharField(max_length=75, blank=True, null=False)
	state = models.CharField(max_length=60, blank=True, null=False)
	zip_code = models.IntegerField(verbose_name='Zip Code',
		choices=ZIP_CODES, blank=True, null=True)
	country = models.CharField(max_length=100, blank=True, null=False)
	document = models.ForeignKey(Document)

	def __str__(self):

		return "%s, %s, %s, %s, %s" % (self.city, self.state, 
									   self.county, 
									   str(self.zip_code), 
									   self.country)

class ShelfMark(models.Model):

	collection_level = models.CharField(max_length=75, blank=True, 
		null=False, verbose_name='Collection Level')
	box_level = models.CharField(max_length=75, blank=True, 
		null=False, verbose_name='Box Level')
	series_level = models.CharField(max_length=75, blank=True, 
		null=False, verbose_name='Series Level')
	folder_level = models.CharField(max_length=75, blank=True, 
		null=False, verbose_name='Folder Level')
	document = models.ForeignKey(Document)

	def __str__(self):

		# shelf_mark = ("%s %s %s %s" % (self.collection_level, self.box_level,
									   # self.series_level, self.folder_level))
		# print("SHELF MARK", shelf_mark, len(shelf_mark))
		# return shelf_mark if len(shelf_mark) != 3 else "NULL: %s" % self.document.__str__()
		return "Document: %s" % self.document.__str__()

class ContributingInstitution(models.Model):

	contributing_institution = models.CharField(blank=False, null=False,
		choices=INSTITUTIONS, max_length=300,
		verbose_name='Contributing Institution')
	document = models.ForeignKey(Document)

	def __str__(self):

		return self.contributing_institution

class AlternativeTitle(models.Model):

	alternative_title = models.CharField(blank=True, null=False,
		max_length=100, verbose_name='Alternative Title')
	document = models.ForeignKey(Document)

	def __str__(self):

		return self.alternative_title
