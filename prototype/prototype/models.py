from django.db import models
from django.conf import settings
from django.utils.encoding import python_2_unicode_compatible
from django.db import connection

from .constants import (CONTENT_TYPE_CHOICES, ARCHIVES, ROLES,
	INSTITUTIONS, FILE_FORMATS, ZIP_CODES, DIGITAL_TYPES)

DIGITAL_TYPES = (
	('Digital Image', 'Digital Image'), 
	('Digital Transcription of Text', 
	 'Digital Transcription of Text'),
)

""" The string representations for all of these need to be
tweaked, they were set to a random base for the prototype. """

@python_2_unicode_compatible
class File(models.Model):

	created = models.DateTimeField(auto_now_add=True)
	modified = models.DateTimeField(auto_now_add=True)

	archive = models.CharField(blank=False, null=False, 
		choices=ARCHIVES, max_length=200, 
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

	shelf_mark = models.CharField(blank=True, null=False,
		max_length=200)
	copyright_holder = models.CharField(blank=False, null=False,
		max_length=100, verbose_name='Copyright Holder')
	# language = models.CharField(blank=False, null=False,
		# choices=settings.LANGUAGES, max_length=10)

	thumbnail_url = models.URLField(blank=True, null=False,
		max_length=100, verbose_name='Thumbnail URL')
	description = models.TextField(blank=True, null=False,
		max_length=2500)

	extent = models.CharField(blank=True, null=False,
		max_length=35)
	genre = models.CharField(blank=True, null=False, max_length=100)

	#Should probs be binary, but whatevs for now \_O_/
	full_text = models.TextField(max_length=50000, verbose_name='Full Text',
		help_text='You may enter the full text for this document in ' +
		'order to allow (OCR) support.')

	use_rights = models.TextField(max_length=10000,
		verbose_name='Usage Rights')
	file_format = models.CharField(blank=False, null=False,
		choices=FILE_FORMATS, max_length=10, verbose_name='File Format')
	notes = models.TextField(blank=True, null=False, max_length=500,
		help_text='Add any additional notes that are pertinent to this ' +
		'document.')

	is_part_of = models.ManyToManyField('self', blank=True, 
		verbose_name='Parent File', related_name='children')
	
	class Meta:

		# unique_together = 
		ordering = ['title']
		get_latest_by = 'created'

	@property
	def uri(self):

		return self.url

	def get_semantic_schema(self):

		pass

	def __str__(self):

		return "%s: %s" % (self.title, self.url)

@python_2_unicode_compatible
class Language(models.Model):

	language = models.CharField(blank=False, null=False,
		choices=settings.LANGUAGES, max_length=3)
	_file = models.ForeignKey(File)

	def __str__(self):

		return "%d: %s" % (self._file.id, self.language)

@python_2_unicode_compatible
class LCSubjectHeading(models.Model):

	lc_subject = models.CharField(blank=False, null=False,
		max_length=150, 
		verbose_name="Library of Congress Subject Heading")
	_file = models.ForeignKey(File)

	class Meta:

		ordering = ['lc_subject']
		verbose_name = "Library of Congress Heading"
		verbose_name_plural = "Library of Congress Headings"

	def __str__(self):

		return "%d: %s" % (self._file.id, self.lc_subject)

@python_2_unicode_compatible
class DigitalType(models.Model):

	type_digital = models.CharField(blank=False, null=False,
		choices=DIGITAL_TYPES, verbose_name="Digital Type",
		max_length=50)
	_file = models.ForeignKey(File)

	class Meta:

		ordering = ['type_digital']
		verbose_name = "Digital Type"
		verbose_name_plural = "Digital Types"

	def __str__(self):

		"%d: %s" % (self._file.id, self.type_digital)

@python_2_unicode_compatible
class Role(models.Model):

	role = models.CharField(blank=False, null=False, 
		choices=ROLES, max_length=30, 
		help_text='Enter the role(s) you\'ve played in the creation ' +
		'of the file you are uploading.')
	name = models.CharField(blank=False, null=False, max_length=100, 
		help_text='Enter your first and last name.')
	_file = models.ForeignKey(File)

	@property
	def formatted_role_metadata(self):

		return "role_%s" % self.role

	def __str__(self):

		return "%d: %s - %s" % (self._file.id, self.role, 
								self.name)

@python_2_unicode_compatible
class GeographicLocation(models.Model):

	human = models.CharField(blank=False, null=False, max_length=75)
	longitude = models.DecimalField(max_digits=9, decimal_places=6,
		help_text='Enter the longitude coordinates of this item in signed degrees.' +
		'You may specify a maximum of nine digits and six decimal places.')
	latitude = models.DecimalField(max_digits=9, decimal_places=6,
		help_text='Enter the latitude coordinates of this item in signed degrees.' +
		'You may specify a maximum of nine digits and six decimal places.')
	_file = models.ForeignKey(File)

	class Meta:

		verbose_name = 'Geographic Location'
		verbose_name_plural = 'Geographic Locations'

	def __str__(self):

		return "%s" % self.human

@python_2_unicode_compatible
class ContributingInstitution(models.Model):

	contributing_institution = models.CharField(blank=False, null=False,
		choices=INSTITUTIONS, max_length=300,
		verbose_name='Contributing Institution')
	_file = models.ForeignKey(File)

	class Meta:

		verbose_name = 'Contributing Institution'
		verbose_name_plural = 'Contributing Institutions'

	def __str__(self):

		return "%d: %s" % (self._file.id, 
						   self.contributing_institution)

@python_2_unicode_compatible
class AlternativeTitle(models.Model):

	alternative_title = models.CharField(blank=True, null=False,
		max_length=100, verbose_name='Alternative Title')
	_file = models.ForeignKey(File)

	class Meta:

		verbose_name = 'Alternative Title'
		verbose_name_plural = 'Alternative Titles'

	def __str__(self):

		return "%d: %s" % (self._file.id, self.alternative_title)