from django.shortcuts import render
from archive.models import Entry

from .forms import ContactForm

def home(request):

    ctx = {'num_docs': Entry.objects.all().count()}
    return render(request, "prototype/home.html", ctx)

def about(request):

	return render(request, "prototype/about.html")

def contact(request):

	if request.method == "POST":
		form = ContactForm(request.POST)
		if form.is_valid():
			messages.succes(request, 
				"Your message has been sent successfully.")
			#send_mail
			return HttpResponseRedirect("/contact/success/")
	else:
		form = ContactForm()
	return render(request, 'prototype/contact.html',
		{'form': form})