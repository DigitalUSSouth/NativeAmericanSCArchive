TO_CHOICES = lambda x: tuple((y, y) for y in x)

DIGITAL_TYPES = (
	'Digital Image',
	'Digital Transcription of Text', 
)

DIGITAL_TYPE_CHOICES = TO_CHOICES(DIGITAL_TYPES)

CONTENT_TYPES = (
	'Image',
	'Text'
)

CONTENT_TYPE_CHOICES = TO_CHOICES(CONTENT_TYPES)

ARCHIVES = (
	'Simms',
	'Ravenel'
)

ARCHIVE_CHOICES = TO_CHOICES(ARCHIVES)

ROLES = (
	'Author',
	'Editor',
	'Publisher',
	'Translator',
	'Creator'
)

ROLE_CHOICES = TO_CHOICES(ROLES)

INSTITUTIONS = (
	'1',
	'2',
	'3',
	'4',
	'5',
)

INSTITUTION_CHOICES = TO_CHOICES(INSTITUTIONS)

FILE_FORMATS = (
	'pdf',
	'txt',
	'html',
	'png',
	'jpeg'
)

FILE_FORMAT_CHOICES = TO_CHOICES(FILE_FORMATS)