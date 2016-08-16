#Internationalization support
from django.utils.translation import ugettext as _, ugettext_noop as _noop

from django.db import models
from django.conf import settings
from django.utils.encoding import python_2_unicode_compatible
from django.db import connection

from .constants import (
	TYPE_DIGITAL, TYPE_CONTENT, ROLE, FILE_FORMAT,
	GENRE, LANGUAGE
)

from utilities import get_max, get_choice_verbose_name

""" Naming schema should align with the metadata schema 
and diagram located at: /Database/Diagram/ERdiagram """

@python_2_unicode_compatible
class Entry(models.Model):

	created = models.DateTimeField(auto_now_add=True)#editable = false, blank = true
	modified = models.DateTimeField(auto_now_add=True)#editable = false, blank = true

	collection = models.CharField(blank=False, null=False,
		max_length=255, verbose_name=_('Collection'),
		help_text=_('The collection which this entry belongs to.')
	)
	#^^ INTENDED TO BE AN AUTOCOMPLETE FIELD ^^

	url = models.URLField(blank=False, null=False, 
		unique=True, max_length=511, 
		verbose_name=_('URL'),
		help_text=_('A unique URI for this archival.'),
	)

	thumbnail_url = models.URLField(blank=True, null=False,
		max_length=511, verbose_name=_('Thumbnail URL'),
		help_text=_('A URI for the thumbnail image of this archival.'),
	)

	video_url = models.URLField(blank=True, null=False,
		max_length=511, verbose_name=_('Embedded Video URL'),
		help_text=_("A URI for the entry's embedded video content, if applicable."),
	)
	
	static_identifier = models.CharField(blank=False, null=False,
		max_length=511, verbose_name=_('Media File Path / Static Identifier'),
		help_text=_("A file path to the associated media on DUSS' static server." +
		" This is usually an image or audio file. Videos are not hosted by DUSS."),
	)

	title = models.CharField(blank=False, null=False, unique=True,
		max_length=511, verbose_name=_('Title'),
		help_text=_('A unique title for the archive entry.'),
	)

	description = models.TextField(blank=True, null=False,
		verbose_name=_('Entry Description'),
		help_text = 'A summary account of the content of this entry.')

	genre = models.CharField(blank=True, null=False,
		verbose_name=_('Literary Genre'), choices=GENRE,
		max_length=get_max(GENRE),
		help_text=_('An optional literary genre, primarily for text entries.'),
	)
	#TODO: Make this reference control list

	full_text = models.TextField(blank=True, null=False,
		verbose_name=_('Full Text'),
		help_text=_('The full text if the entry is a text object, for full text' +
		' (OCR) searching'),
	)

	type_content = models.CharField(blank=False, null=False,
		verbose_name=_('Type of Content'), choices=TYPE_CONTENT,
		max_length=get_max(TYPE_CONTENT),
		help_text=_('The type of content that the entry contains. e.g. text, image, ' +
		'video, etc'),
	)
	#TODO: Make this reference control list

	type_physical = models.CharField(blank=True, null=False,
		max_length=255, verbose_name=_('Type of Physical Artifact'),
		help_text=_('The type of original physical artifact. e.g. Book, manuscript, ' +
		'table, etc.'),
	)
	#^^ INTENDED TO BE AN AUTOCOMPLETE FIELD ^^

	extent = models.CharField(blank=True, null=False,
		max_length=255, verbose_name=_('Extent'),
		help_text=_('The size and/or duration of the original item.'),
	)
	#See MARC 21's 300 $a $b and/or $c fields.

	copyright_holder = models.CharField(blank=True, null=False,
		max_length=255, verbose_name=_('Copyright Holder'),
		help_text=_('The copyright holder of the entry content. i.e. University of ' +
		'South Carolina, Thomas Cooper Library'),
	)

	use_rights = models.TextField(blank=True, null=False,
		verbose_name=_('Usage Rights'),
		help_text=_('The use rights for the material.'),
	)

	file_format = models.CharField(blank=False, null=False,
		choices=FILE_FORMAT, verbose_name=_('File Format'),
		max_length=get_max(FILE_FORMAT),
		help_text=_('The file format of the entry\'s digital surrogate'),
	)
	#TODO: Make this reference control list

	notes = models.TextField(blank=True, null=False,
		verbose_name=_('Notes'),
		help_text=_('Any additional notes pertinent to the document/entry'),
	)

	shelf_mark = models.CharField(blank=True, null=False,
		max_length=2047, verbose_name=_('Source/Shelfmark'),
		help_text=_('A shelfmark for locating the entry item within a physical ' +
		'collection, to any degree of specificity. e.g. USC Student Exams, Folder: 1970s'),
	)

	#is_part_of = models.ManyToManyField('self', blank=True, 
		#verbose_name='Parent File', related_name='children')

	date_range_start = models.DateTimeField(blank=False, null=False,
		verbose_name=_('Date of Original Artifact'),
		help_text=_('Specify the date of the original artifact, or start date if it ' +
		'is of a range of dates. (ISO 8601 date format)'),
	)
	
	date_range_end = models.DateTimeField(blank=True, null=True,
		verbose_name=_('End Date (Range) of Original Artifact'),
		help_text=_('The end of the entry\'s date range, if the artifact has a range ' +
		'of dates associated with it.'),
	)

	date_human = models.CharField(blank=True, null=False,
		max_length=255, verbose_name=_('Date of Original Artifact - Human-Readable'),
		help_text=_('The date(s) of the original artifact in a human-readable format. ' +
		'e.g - 20th Century'),
	)
	
	date_digital = models.DateTimeField(null=False, blank=False,
		verbose_name=_('Date of Digital Surrogate'),
		help_text=_('The date of the digital surrogate. (ISO 8601 date format)'),
	)
	
	date_digital_human = models.CharField(blank=True, null=False,
		max_length=255, verbose_name=_('Date of Digital Surrogate - Human-Readable'),
		help_text=_('The date of the digital surrogate in a human-readable format.'),
	)

	class Meta:

		ordering = ['title']
		get_latest_by = 'created'
		verbose_name_plural = 'Entries'

	@property
	def uri(self):

		return self.url

	def __str__(self):

		return self.title

@python_2_unicode_compatible
class Language(models.Model):

	language = models.CharField(blank=True, null=False,
		choices=LANGUAGE, verbose_name=_('Language'),
		max_length=get_max(LANGUAGE),
		help_text=_('The language of the object.'),
	)
	#TODO: Make this reference control list

	_entry = models.ForeignKey(Entry, on_delete=models.CASCADE)

	def __str__(self):

		return get_choice_verbose_name(self.language, LANGUAGE)

@python_2_unicode_compatible
class LCSubjectHeading(models.Model):

	lc_subject = models.CharField(blank=True, null=False,
		max_length=255, verbose_name=_('Library of Congress Subject Heading'),
		help_text=_(''),
	)

	_entry = models.ForeignKey(Entry, on_delete=models.CASCADE)

	class Meta:

		ordering = ['lc_subject']
		verbose_name = "Library of Congress Heading"
		verbose_name_plural = "Library of Congress Headings"

	def __str__(self):

		return self.lc_subject

@python_2_unicode_compatible
class DigitalType(models.Model):

	type_digital = models.CharField(blank=False, null=False,
		choices=TYPE_DIGITAL, 
		max_length=get_max(TYPE_DIGITAL),
		verbose_name=_("Type of Digital Artifact"),
		help_text=_('The type of digital artifact that is accessible in the entry.'),
	)

	_entry = models.ForeignKey(Entry, on_delete=models.CASCADE)

	class Meta:

		ordering = ['type_digital']
		verbose_name = "Digital Type"
		verbose_name_plural = "Digital Types"

	def __str__(self):

		get_choices_verbose_name(self.type_digital, TYPE_DIGITAL)

@python_2_unicode_compatible
class Role(models.Model):

	role = models.CharField(blank=False, null=False,
		choices=ROLE, verbose_name=_(''), 
		max_length=get_max(ROLE),
		help_text=_('The role(s) played by one or more individuals in the creation ' +
		'of the entry\'s content.'),
	)
	
	individual_name = models.CharField(blank=False, null=False,
		max_length=255, verbose_name=_(''),
		help_text=_('The first and last name of the person associated with the role.'),
	)

	_entry = models.ForeignKey(Entry, on_delete=models.CASCADE)

	@property
	def formatted_role_metadata(self):

		return "role_%s" % self.role

	def __str__(self):

		return "%s - %s" % (get_choice_verbose_name(self.role, ROLE), self.individual_name)

@python_2_unicode_compatible
class GeographicLocation(models.Model):

	human = models.CharField(blank=False, null=False, max_length=75)
	longitude = models.DecimalField(max_digits=9, decimal_places=6,
		help_text=_('Enter the longitude coordinates of this item in signed degrees.' +
		'You may specify a maximum of nine digits and six decimal places.'),
	)
	latitude = models.DecimalField(max_digits=9, decimal_places=6,
		help_text=_('Enter the latitude coordinates of this item in signed degrees.' +
		'You may specify a maximum of nine digits and six decimal places.'),
	)
	_entry = models.ForeignKey(Entry, on_delete=models.CASCADE)

	class Meta:

		verbose_name = 'Geographic Location'
		verbose_name_plural = 'Geographic Locations'

	def __str__(self):

		base = self.human
		if all([self.latitude, self.longitude]):
			base += " - (%.2f, %.2f)" % (self.latitude, self.longitude)
		return base

@python_2_unicode_compatible
class ContributingInstitution(models.Model):

	contributing_institution = models.CharField(blank=False, null=False,
		max_length=255, verbose_name=_('Contributing Institution'),
		help_text=_('The proper title of the institution which owns the physical ' +
		'collection. e.g. University of South Carolina. South Caroliniana Library.'),
	)
	_entry = models.ForeignKey(Entry, on_delete=models.CASCADE)

	class Meta:

		verbose_name = 'Contributing Institution'
		verbose_name_plural = 'Contributing Institutions'

	def __str__(self):

		return self.contributing_institution

@python_2_unicode_compatible
class AlternativeTitle(models.Model):

	alternative_title = models.CharField(blank=True, null=False,
		max_length=511, verbose_name=_('Alternative Title'),
		help_text=_('Any alternative title for the object.'),
	)

	_entry = models.ForeignKey(Entry, on_delete=models.CASCADE)

	class Meta:

		verbose_name = 'Alternative Title'
		verbose_name_plural = 'Alternative Titles'

	def __str__(self):

		return self.alternative_title