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
#CONSTANTS FOR ABSOLUTE CONTROLLED LISTS (AS TUPLES)
#(Please lists order of constants listed prefaced by 3 #'s below)

###
#TYPE_DIGITAL
#TYPE_CONTENT
#ROLE
#FILE_FORMAT
#GENRE
#LANGUAGE

#NOTE:
#Collections/Archives, Contributing Institutions, and Physical Types are all autocomplete fields.
#
#You can write whatever you want and the autocomplete suggestions are distinct values
#that have been entered in the past related to what you are typing
#
#In short they're not constant so they shouldn't go here.
#
#Probably be queried from DB into a prepared statement on page load like this...
#   SELECT DISTINCT collection FROM archive_entry


#[type_digital_id TINYINT(255), type_digital VARCHAR(63), description TEXT(1023)]
TYPE_DIGITAL = [
['0', 'Digital Image'],
['1', 'Digital Transcription of Text'],
]

#[type_content_id TINYINT(255), type_content VARCHAR(63), description TEXT(1023)]
TYPE_CONTENT = [
['0', 'Collection'],
['1', 'Dataset'],
['2', 'Event'],
['3', 'Image'],
['4', 'Interactive Resource'],
['5', 'Moving Image'],
['6', 'Physical Object'],
['7', 'Service'],
['8', 'Software'],
['9', 'Sound'],
['10', 'Still Image'],
['11', 'Text'],
]

#[role VARCHAR(3), role_full VARCHAR(63), description TEXT(1023)]
ROLE = [
['abr','Abridger']
['act','Actor']
['adp','Adapter']
['rcp','Addressee']
['anl','Analyst']
['anm','Animator']
['ann','Annotator']
['apl','Appellant']
['ape','Appellee']
['app','Applicant']
['arc','Architect']
['arr','Arranger']
['acp','Art copyist']
['adi','Art director']
['art','Artist']
['ard','Artistic director']
['asg','Assignee']
['asn','Associated name']
['att','Attributed name']
['auc','Auctioneer']
['aut','Author']
['aqt','Author in quotations or text abstracts']
['aft','Author of afterword, colophon, etc.']
['aud','Author of dialog']
['aui','Author of introduction, etc.']
['ato','Autographer']
['ant','Bibliographic antecedent']
['bnd','Binder']
['bdd','Binding designer']
['blw','Blurb writer']
['bkd','Book designer']
['bkp','Book producer']
['bjd','Bookjacket designer']
['bpd','Bookplate designer']
['bsl','Bookseller']
['brl','Braille embosser']
['brd','Broadcaster']
['cll','Calligrapher']
['ctg','Cartographer']
['cas','Caster']
['cns','Censor']
['chr','Choreographer']
['cng','Cinematographer']
['cli','Client']
['cor','Collection registrar']
['col','Collector']
['clt','Collotyper']
['clr','Colorist']
['cmm','Commentator']
['cwt','Commentator for written text']
['com','Compiler']
['cpl','Complainant']
['cpt','Complainant-appellant']
['cpe','Complainant-appellee']
['cmp','Composer']
['cmt','Compositor']
['ccp','Conceptor']
['cnd','Conductor']
['con','Conservator']
['csl','Consultant']
['csp','Consultant to a project']
['cos','Contestant']
['cot','Contestant-appellant']
['coe','Contestant-appellee']
['cts','Contestee']
['ctt','Contestee-appellant']
['cte','Contestee-appellee']
['ctr','Contractor']
['ctb','Contributor']
['cpc','Copyright claimant']
['cph','Copyright holder']
['crr','Corrector']
['crp','Correspondent']
['cst','Costume designer']
['cou','Court governed']
['crt','Court reporter']
['cov','Cover designer']
['cre','Creator']
['cur','Curator']
['dnc','Dancer']
['dtc','Data contributor']
['dtm','Data manager']
['dte','Dedicatee']
['dto','Dedicator']
['dfd','Defendant']
['dft','Defendant-appellant']
['dfe','Defendant-appellee']
['dgg','Degree granting institution']
['dgs','Degree supervisor']
['dln','Delineator']
['dpc','Depicted']
['dpt','Depositor']
['dsr','Designer']
['drt','Director']
['dis','Dissertant']
['dbp','Distribution place']
['dst','Distributor']
['dnr','Donor']
['drm','Draftsman']
['dub','Dubious author']
['edt','Editor']
['edc','Editor of compilation']
['edm','Editor of moving image work']
['elg','Electrician']
['elt','Electrotyper']
['enj','Enacting jurisdiction']
['eng','Engineer']
['egr','Engraver']
['etr','Etcher']
['evp','Event place']
['exp','Expert']
['fac','Facsimilist']
['fld','Field director']
['fmd','Film director']
['fds','Film distributor']
['flm','Film editor']
['fmp','Film producer']
['fmk','Filmmaker']
['fpy','First party']
['frg','Forger']
['fmo','Former owner']
['fnd','Funder']
['gis','Geographic information specialist']
['hnr','Honoree']
['hst','Host']
['his','Host institution']
['ilu','Illuminator']
['ill','Illustrator']
['ins','Inscriber']
['itr','Instrumentalist']
['ive','Interviewee']
['ivr','Interviewer']
['inv','Inventor']
['isb','Issuing body']
['jud','Judge']
['jug','Jurisdiction governed']
['lbr','Laboratory']
['ldr','Laboratory director']
['lsa','Landscape architect']
['led','Lead']
['len','Lender']
['lil','Libelant']
['lit','Libelant-appellant']
['lie','Libelant-appellee']
['lel','Libelee']
['let','Libelee-appellant']
['lee','Libelee-appellee']
['lbt','Librettist']
['lse','Licensee']
['lso','Licensor']
['lgd','Lighting designer']
['ltg','Lithographer']
['lyr','Lyricist']
['mfp','Manufacture place']
['mfr','Manufacturer']
['mrb','Marbler']
['mrk','Markup editor']
['med','Medium']
['mdc','Metadata contact']
['mte','Metal-engraver']
['mtk','Minute taker']
['mod','Moderator']
['mon','Monitor']
['mcp','Music copyist']
['msd','Musical director']
['mus','Musician']
['nrt','Narrator']
['osp','Onscreen presenter']
['opn','Opponent']
['orm','Organizer']
['org','Originator']
['oth','Other']
['own','Owner']
['pan','Panelist']
['ppm','Papermaker']
['pta','Patent applicant']
['pth','Patent holder']
['pat','Patron']
['prf','Performer']
['pma','Permitting agency']
['pht','Photographer']
['ptf','Plaintiff']
['ptt','Plaintiff-appellant']
['pte','Plaintiff-appellee']
['plt','Platemaker']
['pra','Praeses']
['pre','Presenter']
['prt','Printer']
['pop','Printer of plates']
['prm','Printmaker']
['prc','Process contact']
['pro','Producer']
['prn','Production company']
['prs','Production designer']
['pmn','Production manager']
['prd','Production personnel']
['prp','Production place']
['prg','Programmer']
['pdr','Project director']
['pfr','Proofreader']
['prv','Provider']
['pup','Publication place']
['pbl','Publisher']
['pbd','Publishing director']
['ppt','Puppeteer']
['rdd','Radio director']
['rpc','Radio producer']
['rce','Recording engineer']
['rcd','Recordist']
['red','Redaktor']
['ren','Renderer']
['rpt','Reporter']
['rps','Repository']
['rth','Research team head']
['rtm','Research team member']
['res','Researcher']
['rsp','Respondent']
['rst','Respondent-appellant']
['rse','Respondent-appellee']
['rpy','Responsible party']
['rsg','Restager']
['rsr','Restorationist']
['rev','Reviewer']
['rbr','Rubricator']
['sce','Scenarist']
['sad','Scientific advisor']
['aus','Screenwriter']
['scr','Scribe']
['scl','Sculptor']
['spy','Second party']
['sec','Secretary']
['sll','Seller']
['std','Set designer']
['stg','Setting']
['sgn','Signer']
['sng','Singer']
['sds','Sound designer']
['spk','Speaker']
['spn','Sponsor']
['sgd','Stage director']
['stm','Stage manager']
['stn','Standards body']
['str','Stereotyper']
['stl','Storyteller']
['sht','Supporting host']
['srv','Surveyor']
['tch','Teacher']
['tcd','Technical director']
['tld','Television director']
['tlp','Television producer']
['ths','Thesis advisor']
['trc','Transcriber']
['trl','Translator']
['tyd','Type designer']
['tyg','Typographer']
['uvp','University place']
['vdg','Videographer']
['vac','Voice actor']
['wit','Witness']
['wde','Wood engraver']
['wdc','Woodcutter']
['wam','Writer of accompanying material']
['wac','Writer of added commentary']
['wal','Writer of added lyrics']
['wat','Writer of added text']
['win','Writer of introduction']
['wpr','Writer of preface']
['wst','Writer of supplementary textual content']
]

#[file_format_extension VARCHAR(7), file_format_mime VARCHAR(255), file_format_hrname]
FILE_FORMAT = [
['application/pdf', 'Adobe Portable Document Format'],
['audio/x-aac', 'Advanced Audio Coding (AAC)'],
['audio/vnd.dts', 'DTS Audio'],
['audio/vnd.dts.hd', 'DTS High Definition Audio'],
['video/x-f4v', 'Flash Video'],
['video/x-flv', 'Flash Video'],
['video/h264', 'H.264'],
['text/html', 'HyperText Markup Language (HTML)'],
['application/vnd.ms-excel', 'Microsoft Excel'],
['application/vnd.ms-excel.addin.macroenabled.12', 'Microsoft Excel - Add-In File'],
['application/vnd.ms-excel.sheet.binary.macroenabled.12', 'Microsoft Excel - Binary Workbook'],
['application/vnd.ms-excel.template.macroenabled.12', 'Microsoft Excel - Macro-Enabled Template File'],
['application/vnd.ms-excel.sheet.macroenabled.12', 'Microsoft Excel - Macro-Enabled Workbook'],
['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'Microsoft Office - OOXML - Spreadsheet'],
['application/vnd.openxmlformats-officedocument.spreadsheetml.template', 'Microsoft Office - OOXML - Spreadsheet Template'],
['audio/mp4', 'MPEG-4 Audio'],
['video/mp4', 'MPEG-4 Video'],
['image/svg+xml', 'Scalable Vector Graphics (SVG)'],
['image/tiff', 'Tagged Image File Format'],
['audio/x-wav', 'Waveform Audio File Format (WAV)'],
['application/xhtml+xml', 'XHTML - The Extensible HyperText Markup Language'],
['application/xml', 'XML - Extensible Markup Language'],
['audio/x-aiff', 'Audio Interchange File Format'],
['video/webm', 'Open Web Media Project - Video'],
['audio/webm', 'Open Web Media Project - Audio'],
['image/webp', 'WebP Image'],
['image/bmp', 'Bitmap Image File'],
['image/vnd.dvb.subtitle', 'Close Captioning - Subtitle'],
['image/gif', 'Graphics Interchange Format'],
['image/x-icon', 'Icon Image'],
['image/jpeg', 'JPEG Image'],
['image/png', 'Portable Network Graphics (PNG)'],
]

#[genre_id TINYINT(255), genre VARCHAR(63), description TEXT(1023)]
GENRE = [
['0', 'Bibliography'],
['1', 'Catalog'],
['2', 'Citation'],
['3', 'Collection'],
['4', 'Correspondence'],
['5', 'Criticism'],
['6', 'Drama'],
['7', 'Ephemera'],
['8', 'Fiction'],
['9', 'Historiography'],
['10', 'Law'],
['11', 'Life Writing'],
['12', 'Liturgy'],
['13', 'Musical Analysis'],
['14', 'Music, Other'],
['15', 'Musical Recording'],
['16', 'Musical Score'],
['17', 'Nonfiction'],
['18', 'Paratext'],
['19', 'Philosophy'],
['20', 'Photograph'],
['21', 'Poetry'],
['22', 'Religion'],
['23', 'Religion, Other'],
['24', 'Reference Works'],
['25', 'Review'],
['26', 'Scripture'],
['27', 'Sermon'],
['28', 'Translation'],
['29', 'Travel Writing'],
['30', 'Unspecified'],
['31', 'Visual Art'],
]

#[language VARCHAR(3), language_full VARCHAR(127)]
LANGUAGE = [
['aar','Afar'],
['abk','Abkhazian'],
['ace','Achinese'],
['ach','Acoli'],
['ada','Adangme'],
['ady','Adyghe; Adygei'],
['afa','Afro-Asiatic languages'],
['afh','Afrihili'],
['afr','Afrikaans'],
['ain','Ainu'],
['aka','Akan'],
['akk','Akkadian'],
['alb','Albanian'],
['ale','Aleut'],
['alg','Algonquian languages'],
['alt','Southern Altai'],
['amh','Amharic'],
['ang','English, Old (ca.450-1100)'],
['anp','Angika'],
['apa','Apache languages'],
['ara','Arabic'],
['arc','Official Aramaic (700-300 BCE); Imperial Aramaic (700-300 BCE)'],
['arg','Aragonese'],
['arm','Armenian'],
['arn','Mapudungun; Mapuche'],
['arp','Arapaho'],
['art','Artificial languages'],
['arw','Arawak'],
['asm','Assamese'],
['ast','Asturian; Bable; Leonese; Asturleonese'],
['ath','Athapascan languages'],
['aus','Australian languages'],
['ava','Avaric'],
['ave','Avestan'],
['awa','Awadhi'],
['aym','Aymara'],
['aze','Azerbaijani'],
['bad','Banda languages'],
['bai','Bamileke languages'],
['bak','Bashkir'],
['bal','Baluchi'],
['bam','Bambara'],
['ban','Balinese'],
['baq','Basque'],
['bas','Basa'],
['bat','Baltic languages'],
['bej','Beja; Bedawiyet'],
['bel','Belarusian'],
['bem','Bemba'],
['ben','Bengali'],
['ber','Berber languages'],
['bho','Bhojpuri'],
['bih','Bihari languages'],
['bik','Bikol'],
['bin','Bini; Edo'],
['bis','Bislama'],
['bla','Siksika'],
['bnt','Bantu languages'],
['tib','Tibetan'],
['bos','Bosnian'],
['bra','Braj'],
['bre','Breton'],
['btk','Batak languages'],
['bua','Buriat'],
['bug','Buginese'],
['bul','Bulgarian'],
['bur','Burmese'],
['byn','Blin; Bilin'],
['cad','Caddo'],
['cai','Central American Indian languages'],
['car','Galibi Carib'],
['cat','Catalan; Valencian'],
['cau','Caucasian languages'],
['ceb','Cebuano'],
['cel','Celtic languages'],
['cze','Czech'],
['cha','Chamorro'],
['chb','Chibcha'],
['che','Chechen'],
['chg','Chagatai'],
['chi','Chinese'],
['chk','Chuukese'],
['chm','Mari'],
['chn','Chinook jargon'],
['cho','Choctaw'],
['chp','Chipewyan; Dene Suline'],
['chr','Cherokee'],
['chu','Church Slavic; Old Slavonic; Church Slavonic; Old Bulgarian; Old Church Slavonic'],
['chv','Chuvash'],
['chy','Cheyenne'],
['cmc','Chamic languages'],
['cop','Coptic'],
['cor','Cornish'],
['cos','Corsican'],
['cpe','Creoles and pidgins, English based'],
['cpf','Creoles and pidgins, French-based'],
['cpp','Creoles and pidgins, Portuguese-based'],
['cre','Cree'],
['crh','Crimean Tatar; Crimean Turkish'],
['crp','Creoles and pidgins'],
['csb','Kashubian'],
['cus','Cushitic languages'],
['wel','Welsh'],
['cze','Czech'],
['dak','Dakota'],
['dan','Danish'],
['dar','Dargwa'],
['day','Land Dayak languages'],
['del','Delaware'],
['den','Slave (Athapascan)'],
['ger','German'],
['dgr','Dogrib'],
['din','Dinka'],
['div','Divehi; Dhivehi; Maldivian'],
['doi','Dogri'],
['dra','Dravidian languages'],
['dsb','Lower Sorbian'],
['dua','Duala'],
['dum','Dutch, Middle (ca.1050-1350)'],
['dut','Dutch; Flemish'],
['dyu','Dyula'],
['dzo','Dzongkha'],
['efi','Efik'],
['egy','Egyptian (Ancient)'],
['eka','Ekajuk'],
['gre','Greek, Modern (1453-)'],
['elx','Elamite'],
['eng','English'],
['enm','English, Middle (1100-1500)'],
['epo','Esperanto'],
['est','Estonian'],
['baq','Basque'],
['ewe','Ewe'],
['ewo','Ewondo'],
['fan','Fang'],
['fao','Faroese'],
['per','Persian'],
['fat','Fanti'],
['fij','Fijian'],
['fil','Filipino; Pilipino'],
['fin','Finnish'],
['fiu','Finno-Ugrian languages'],
['fon','Fon'],
['fre','French'],
['fre','French'],
['frm','French, Middle (ca.1400-1600)'],
['fro','French, Old (842-ca.1400)'],
['frr','Northern Frisian'],
['frs','Eastern Frisian'],
['fry','Western Frisian'],
['ful','Fulah'],
['fur','Friulian'],
['gaa','Ga'],
['gay','Gayo'],
['gba','Gbaya'],
['gem','Germanic languages'],
['geo','Georgian'],
['ger','German'],
['gez','Geez'],
['gil','Gilbertese'],
['gla','Gaelic; Scottish Gaelic'],
['gle','Irish'],
['glg','Galician'],
['glv','Manx'],
['gmh','German, Middle High (ca.1050-1500)'],
['goh','German, Old High (ca.750-1050)'],
['gon','Gondi'],
['gor','Gorontalo'],
['got','Gothic'],
['grb','Grebo'],
['grc','Greek, Ancient (to 1453)'],
['gre','Greek, Modern (1453-)'],
['grn','Guarani'],
['gsw','Swiss German; Alemannic; Alsatian'],
['guj','Gujarati'],
['gwi','Gwich\'in'],
['hai','Haida'],
['hat','Haitian; Haitian Creole'],
['hau','Hausa'],
['haw','Hawaiian'],
['heb','Hebrew'],
['her','Herero'],
['hil','Hiligaynon'],
['him','Himachali languages; Western Pahari languages'],
['hin','Hindi'],
['hit','Hittite'],
['hmn','Hmong; Mong'],
['hmo','Hiri Motu'],
['hrv','Croatian'],
['hsb','Upper Sorbian'],
['hun','Hungarian'],
['hup','Hupa'],
['arm','Armenian'],
['iba','Iban'],
['ibo','Igbo'],
['ice','Icelandic'],
['ido','Ido'],
['iii','Sichuan Yi; Nuosu'],
['ijo','Ijo languages'],
['iku','Inuktitut'],
['ile','Interlingue; Occidental'],
['ilo','Iloko'],
['ina','Interlingua (International Auxiliary Language Association)'],
['inc','Indic languages'],
['ind','Indonesian'],
['ine','Indo-European languages'],
['inh','Ingush'],
['ipk','Inupiaq'],
['ira','Iranian languages'],
['iro','Iroquoian languages'],
['ice','Icelandic'],
['ita','Italian'],
['jav','Javanese'],
['jbo','Lojban'],
['jpn','Japanese'],
['jpr','Judeo-Persian'],
['jrb','Judeo-Arabic'],
['kaa','Kara-Kalpak'],
['kab','Kabyle'],
['kac','Kachin; Jingpho'],
['kal','Kalaallisut; Greenlandic'],
['kam','Kamba'],
['kan','Kannada'],
['kar','Karen languages'],
['kas','Kashmiri'],
['geo','Georgian'],
['kau','Kanuri'],
['kaw','Kawi'],
['kaz','Kazakh'],
['kbd','Kabardian'],
['kha','Khasi'],
['khi','Khoisan languages'],
['khm','Central Khmer'],
['kho','Khotanese; Sakan'],
['kik','Kikuyu; Gikuyu'],
['kin','Kinyarwanda'],
['kir','Kirghiz; Kyrgyz'],
['kmb','Kimbundu'],
['kok','Konkani'],
['kom','Komi'],
['kon','Kongo'],
['kor','Korean'],
['kos','Kosraean'],
['kpe','Kpelle'],
['krc','Karachay-Balkar'],
['krl','Karelian'],
['kro','Kru languages'],
['kru','Kurukh'],
['kua','Kuanyama; Kwanyama'],
['kum','Kumyk'],
['kur','Kurdish'],
['kut','Kutenai'],
['lad','Ladino'],
['lah','Lahnda'],
['lam','Lamba'],
['lao','Lao'],
['lat','Latin'],
['lav','Latvian'],
['lez','Lezghian'],
['lim','Limburgan; Limburger; Limburgish'],
['lin','Lingala'],
['lit','Lithuanian'],
['lol','Mongo'],
['loz','Lozi'],
['ltz','Luxembourgish; Letzeburgesch'],
['lua','Luba-Lulua'],
['lub','Luba-Katanga'],
['lug','Ganda'],
['lui','Luiseno'],
['lun','Lunda'],
['luo','Luo (Kenya and Tanzania)'],
['lus','Lushai'],
['mac','Macedonian'],
['mad','Madurese'],
['mag','Magahi'],
['mah','Marshallese'],
['mai','Maithili'],
['mak','Makasar'],
['mal','Malayalam'],
['man','Mandingo'],
['mao','Maori'],
['map','Austronesian languages'],
['mar','Marathi'],
['mas','Masai'],
['may','Malay'],
['mdf','Moksha'],
['mdr','Mandar'],
['men','Mende'],
['mga','Irish, Middle (900-1200)'],
['mic','Mi\'kmaq; Micmac'],
['min','Minangkabau'],
['mis','Uncoded languages'],
['mac','Macedonian'],
['mkh','Mon-Khmer languages'],
['mlg','Malagasy'],
['mlt','Maltese'],
['mnc','Manchu'],
['mni','Manipuri'],
['mno','Manobo languages'],
['moh','Mohawk'],
['mon','Mongolian'],
['mos','Mossi'],
['mao','Maori'],
['may','Malay'],
['mul','Multiple languages'],
['mun','Munda languages'],
['mus','Creek'],
['mwl','Mirandese'],
['mwr','Marwari'],
['bur','Burmese'],
['myn','Mayan languages'],
['myv','Erzya'],
['nah','Nahuatl languages'],
['nai','North American Indian languages'],
['nap','Neapolitan'],
['nau','Nauru'],
['nav','Navajo; Navaho'],
['nbl','Ndebele, South; South Ndebele'],
['nde','Ndebele, North; North Ndebele'],
['ndo','Ndonga'],
['nds','Low German; Low Saxon; German, Low; Saxon, Low'],
['nep','Nepali'],
['new','Nepal Bhasa; Newari'],
['nia','Nias'],
['nic','Niger-Kordofanian languages'],
['niu','Niuean'],
['dut','Dutch; Flemish'],
['nno','Norwegian Nynorsk; Nynorsk, Norwegian'],
['nob','Bokmål, Norwegian; Norwegian Bokmål'],
['nog','Nogai'],
['non','Norse, Old'],
['nor','Norwegian'],
['nqo','N\'Ko'],
['nso','Pedi; Sepedi; Northern Sotho'],
['nub','Nubian languages'],
['nwc','Classical Newari; Old Newari; Classical Nepal Bhasa'],
['nya','Chichewa; Chewa; Nyanja'],
['nym','Nyamwezi'],
['nyn','Nyankole'],
['nyo','Nyoro'],
['nzi','Nzima'],
['oci','Occitan (post 1500)'],
['oji','Ojibwa'],
['ori','Oriya'],
['orm','Oromo'],
['osa','Osage'],
['oss','Ossetian; Ossetic'],
['ota','Turkish, Ottoman (1500-1928)'],
['oto','Otomian languages'],
['paa','Papuan languages'],
['pag','Pangasinan'],
['pal','Pahlavi'],
['pam','Pampanga; Kapampangan'],
['pan','Panjabi; Punjabi'],
['pap','Papiamento'],
['pau','Palauan'],
['peo','Persian, Old (ca.600-400 B.C.)'],
['per','Persian'],
['phi','Philippine languages'],
['phn','Phoenician'],
['pli','Pali'],
['pol','Polish'],
['pon','Pohnpeian'],
['por','Portuguese'],
['pra','Prakrit languages'],
['pro','Provençal, Old (to 1500);Occitan, Old (to 1500)'],
['pus','Pushto; Pashto'],
['qaa','Reserved for local use'],
['que','Quechua'],
['raj','Rajasthani'],
['rap','Rapanui'],
['rar','Rarotongan; Cook Islands Maori'],
['roa','Romance languages'],
['roh','Romansh'],
['rom','Romany'],
['rum','Romanian; Moldavian; Moldovan'],
['rum','Romanian; Moldavian; Moldovan'],
['run','Rundi'],
['rup','Aromanian; Arumanian; Macedo-Romanian'],
['rus','Russian'],
['sad','Sandawe'],
['sag','Sango'],
['sah','Yakut'],
['sai','South American Indian languages'],
['sal','Salishan languages'],
['sam','Samaritan Aramaic'],
['san','Sanskrit'],
['sas','Sasak'],
['sat','Santali'],
['scn','Sicilian'],
['sco','Scots'],
['sel','Selkup'],
['sem','Semitic languages'],
['sga','Irish, Old (to 900)'],
['sgn','Sign Languages'],
['shn','Shan'],
['sid','Sidamo'],
['sin','Sinhala; Sinhalese'],
['sio','Siouan languages'],
['sit','Sino-Tibetan languages'],
['sla','Slavic languages'],
['slo','Slovak'],
['slo','Slovak'],
['slv','Slovenian'],
['sma','Southern Sami'],
['sme','Northern Sami'],
['smi','Sami languages'],
['smj','Lule Sami'],
['smn','Inari Sami'],
['smo','Samoan'],
['sms','Skolt Sami'],
['sna','Shona'],
['snd','Sindhi'],
['snk','Soninke'],
['sog','Sogdian'],
['som','Somali'],
['son','Songhai languages'],
['sot','Sotho, Southern'],
['spa','Spanish; Castilian'],
['alb','Albanian'],
['srd','Sardinian'],
['srn','Sranan Tongo'],
['srp','Serbian'],
['srr','Serer'],
['ssa','Nilo-Saharan languages'],
['ssw','Swati'],
['suk','Sukuma'],
['sun','Sundanese'],
['sus','Susu'],
['sux','Sumerian'],
['swa','Swahili'],
['swe','Swedish'],
['syc','Classical Syriac'],
['syr','Syriac'],
['tah','Tahitian'],
['tai','Tai languages'],
['tam','Tamil'],
['tat','Tatar'],
['tel','Telugu'],
['tem','Timne'],
['ter','Tereno'],
['tet','Tetum'],
['tgk','Tajik'],
['tgl','Tagalog'],
['tha','Thai'],
['tib','Tibetan'],
['tig','Tigre'],
['tir','Tigrinya'],
['tiv','Tiv'],
['tkl','Tokelau'],
['tlh','Klingon; tlhIngan-Hol'],
['tli','Tlingit'],
['tmh','Tamashek'],
['tog','Tonga (Nyasa)'],
['ton','Tonga (Tonga Islands)'],
['tpi','Tok Pisin'],
['tsi','Tsimshian'],
['tsn','Tswana'],
['tso','Tsonga'],
['tuk','Turkmen'],
['tum','Tumbuka'],
['tup','Tupi languages'],
['tur','Turkish'],
['tut','Altaic languages'],
['tvl','Tuvalu'],
['twi','Twi'],
['tyv','Tuvinian'],
['udm','Udmurt'],
['uga','Ugaritic'],
['uig','Uighur; Uyghur'],
['ukr','Ukrainian'],
['umb','Umbundu'],
['und','Undetermined'],
['urd','Urdu'],
['uzb','Uzbek'],
['vai','Vai'],
['ven','Venda'],
['vie','Vietnamese'],
['vol','Volapük'],
['vot','Votic'],
['wak','Wakashan languages'],
['wal','Wolaitta; Wolaytta'],
['war','Waray'],
['was','Washo'],
['wel','Welsh'],
['wen','Sorbian languages'],
['wln','Walloon'],
['wol','Wolof'],
['xal','Kalmyk; Oirat'],
['xho','Xhosa'],
['yao','Yao'],
['yap','Yapese'],
['yid','Yiddish'],
['yor','Yoruba'],
['ypk','Yupik languages'],
['zap','Zapotec'],
['zbl','Blissymbols; Blissymbolics; Bliss'],
['zen','Zenaga'],
['zgh','Standard Moroccan Tamazight'],
['zha','Zhuang; Chuang'],
['chi','Chinese'],
['znd','Zande languages'],
['zul','Zulu'],
['zun','Zuni'],
['zxx','No linguistic content; Not applicable'],
['zza','Zaza; Dimili; Dimli; Kirdki; Kirmanjki; Zazaki'],
]