from django.db import models
from django.conf import settings
from django.utils.encoding import python_2_unicode_compatible
from django.db import connection

#THESE ARE JUST TUPLE CONSTANTS
#WILL BE REPLACED BY CONTROLLED LISTS IN DATABASE DOWN THE LINE
from .constants import (TYPE_DIGITAL, TYPE_CONTENT, ROLE, FILE_FORMAT,
						GENRE, LANGUAGE)

#NAMING SCHEME SHOULD ALIGN WITH
#METADATA SCHEMA
#AND
#DIAGRAM AT /Database/Diagram/ERdiagram

@python_2_unicode_compatible
class Entry(models.Model):

	created = models.DateTimeField(auto_now_add=True)#editable = false, blank = true
	modified = models.DateTimeField(auto_now_add=True)#editable = false, blank = true

	collection = models.CharField(blank = False, null = False, #unique = False,
		max_length=255, verbose_name = 'Entry Collection',
		help_text = 'The collection which this entry belongs to.')
	#^^ INTENDED TO BE AN AUTOCOMPLETE FIELD ^^

	url = models.URLField(blank = False, null = False, unique = True,
		max_length = 511, verbose_name = 'Entry URL'
		help_text = 'A unique URI for this archival.')

	thumbnail_url = models.URLField(blank = True, null = False, #unique = False,
		max_length = 511, verbose_name = 'Entry Thumbnail URL',
		help_text = 'A URI for the thumbnail image of this archival.')

	video_url = models.URLField(blank = True, null = False, #unique = False,
		max_length = 511, verbose_name = 'Embedded Video URL',
		help_text = 'A URI for the entry\'s embedded video content, if applicable.')
	
	static_identifier = models.CharField(blank = False, null = False, #unique = False,
		max_length = 511, verbose_name = 'Media File Path / Static Identifier',
		help_text = 'A file path to the associated media on DUSS\' static server.' +
		' This is usually an image or audio file. Videos are not hosted by DUSS.')

	title = models.CharField(blank = False, null = False, unique = True,
		max_length = 511, verbose_name = 'Entry Title'
		help_text = 'A unique title for the archive entry.')

	description = models.TextField(blank = True, null = False, #unique = False,
		verbose_name = 'Entry Description',
		help_text = 'A summary account of the content of this entry.')

	genre = models.CharField(blank = True, null = False, #unique = False,
		verbose_name = 'Literary Genre', choices = GENRE
		help_text = 'An optional literary genre, primarily for text entries.')
	#TODO: Make this reference control list

	full_text = models.TextField(blank = True, null = False, #unique = False,
		verbose_name='Full Text',
		help_text='The full text if the entry is a text object, for full text (OCR) ' +
		'searching')

	type_content = models.CharField(blank = False, null = False, #unique = False,
		verbose_name = 'Type of Content', choices = TYPE_CONTENT
		help_text = 'The type of content that the entry contains. e.g. text, image, ' +
		'video, etc')
	#TODO: Make this reference control list

	type_physical = models.CharField(blank = True, null = False, #unique = False,
		max_length = 255, verbose_name = 'Type of Physical Artifact',
		help_text = 'The type of original physical artifact. e.g. Book, manuscript, ' +
		'table, etc.')
	#^^ INTENDED TO BE AN AUTOCOMPLETE FIELD ^^

	extent = models.CharField(blank = True, null = False, #unique = False,
		max_length = 255, verbose_name = 'Extent/Size/Duration',
		help_text = 'The size and/or duration of the original item.')
	#See MARC 21's 300 $a $b and/or $c fields.

	copyright_holder = models.CharField(blank=True, null=False, #unique = False,
		max_length=255, verbose_name='Copyright Holder',
		help_text = 'The copyright holder of the entry content. i.e. University of ' +
		'South Carolina, Thomas Cooper Library')

	use_rights = models.TextField(blank = True, null = False, #unique = False,
		verbose_name = 'Usage Rights',
		help_text = 'The use rights for the material.')

	file_format = models.CharField(blank = False, null = False, #unique = False,
		choices = FILE_FORMAT, verbose_name = 'File Format',
		help_text = 'The file format of the entry\'s digital surrogate')
	#TODO: Make this reference control list

	notes = models.TextField(blank=True, null=False, #unique = False,
		verbose_name = 'Notes',
		help_text = 'Any additional notes pertinent to the document/entry')

	shelf_mark = models.CharField(blank = True, null = False, #unique = False,
		max_length = 2047, verbose_name = 'Source/Shelfmark',
		help_text = 'A shelfmark for locating the entry item within a physical ' +
		'collection, to any degree of specificity. e.g. USC Student Exams, Folder: 1970s')

	#is_part_of = models.ManyToManyField('self', blank=True, 
		#verbose_name='Parent File', related_name='children')

	#ALL DATE FIELDS start
	date_range_start = models.DateTimeField(blank = False, null = False, #unique = False,
		verbose_name = 'Date of Original Artifact',
		help_text = 'Specify the date of the original artifact, or start date if it ' +
		'is of a range of dates. (ISO 8601 date format)')
	
	date_range_end = models.DateTimeField(blank = True, null = True, #unique = False,
		verbose_name = 'End Date (Range) of Original Artifact',
		help_text = 'The end of the entry\'s date range, if the artifact has a range ' +
		'of dates associated with it.')

	date_human = models.CharField(blank = True, null = False, #unique = False,
		max_length = 255, verbose_name = 'Date of Original Artifact - Human-Readable',
		help_text = 'The date(s) of the original artifact in a human-readable format. ' +
		'e.g - 20th Century')
	
	date_digital = models.DateTimeField(null = False, blank = False, #unique = False,
		verbose_name = 'Date of Digital Surrogate',
		help_text = 'The date of the digital surrogate. (ISO 8601 date format)')
	
	date_digital_human = models.CharField(blank = True, null = False, #unique = False,
		max_length = 255, verbose_name = 'Date of Digital Surrogate - Human-Readable',
		help_text = 'The date of the digital surrogate in a human-readable format.')
	#ALL DATE FIELDS end

	class Meta:

		# unique_together = 
		ordering = ['title']
		get_latest_by = 'created'

	@property
	def uri(self):

		return self.url

	def __str__(self):

		return "%s: %s" % (self.title, self.url)

@python_2_unicode_compatible
class Language(models.Model):

	language = models.CharField(blank = True, null = False, #unique = False,
		choices = LANGUAGE, verbose_name = 'Language',
		help_text = 'The language of the object.')
	#TODO: Make this reference control list

	_entry = models.ForeignKey(Entry)

	def __str__(self):

		return "%d: %s" % (self._entry.id, self.language)

@python_2_unicode_compatible
class LCSubjectHeading(models.Model):

	lc_subject = models.CharField(blank = True, null = False, #unique = False
		max_length = 255, verbose_name = 'Library of Congress Subject Heading',
		help_text = '')

	_entry = models.ForeignKey(Entry)

	class Meta:

		ordering = ['lc_subject']
		verbose_name = "Library of Congress Heading"
		verbose_name_plural = "Library of Congress Headings"

	def __str__(self):

		return "%d: %s" % (self._entry.id, self.lc_subject)

@python_2_unicode_compatible
class DigitalType(models.Model):

	type_digital = models.CharField(blank = False, null = False, #unique = False
		choices = TYPE_DIGITAL, verbose_name = "Type of Digital Artifact",
		help_text = 'The type of digital artifact that is accessible in the entry.')

	_entry = models.ForeignKey(Entry)

	class Meta:

		ordering = ['type_digital']
		verbose_name = "Digital Type"
		verbose_name_plural = "Digital Types"

	def __str__(self):

		"%d: %s" % (self._entry.id, self.type_digital)

@python_2_unicode_compatible
class Role(models.Model):

	role = models.CharField(blank = False, null = False, #unique = False
		choices = ROLE, verbose_name = '', 
		help_text='The role(s) played by one or more individuals in the creation ' +
		'of the entry\'s content.')
	
	individual_name = models.CharField(blank = False, null = False, #unique = False
		max_length = 255, verbose_name = '',
		help_text = 'The first and last name of the person associated with the role.')
	
	_entry = models.ForeignKey(Entry)

	@property
	def formatted_role_metadata(self):

		return "role_%s" % self.role

	def __str__(self):

		return "%d: %s - %s" % (self._entry.id, self.role, 
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
	_entry = models.ForeignKey(Entry)

	class Meta:

		verbose_name = 'Geographic Location'
		verbose_name_plural = 'Geographic Locations'

	def __str__(self):

		return "%s" % self.human

@python_2_unicode_compatible
class ContributingInstitution(models.Model):

	contributing_institution = models.CharField(blank = False, null = False, #unique = False,
		max_length = 255, verbose_name = 'Contributing Institution',
		help_text = 'The proper title of the institution which owns the physical ' +
		'collection. e.g. University of South Carolina. South Caroliniana Library.')
	#^^ INTENDED TO BE AN AUTOCOMPLETE FIELD ^^

	_entry = models.ForeignKey(Entry)

	class Meta:

		verbose_name = 'Contributing Institution'
		verbose_name_plural = 'Contributing Institutions'

	def __str__(self):

		return "%d: %s" % (self._entry.id, 
						   self.contributing_institution)

@python_2_unicode_compatible
class AlternativeTitle(models.Model):

	alternative_title = models.CharField(blank = True, null = False, #unique = False
		max_length = 511, verbose_name = 'Alternative Title',
		help_text = 'Any alternative title for the object.')

	_entry = models.ForeignKey(Entry)

	class Meta:

		verbose_name = 'Alternative Title'
		verbose_name_plural = 'Alternative Titles'

	def __str__(self):

		return "%d: %s" % (self._entry.id, self.alternative_title)