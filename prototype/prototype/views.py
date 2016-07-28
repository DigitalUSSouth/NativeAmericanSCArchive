from django.shortcuts import render
from django.http import HttpResponseRedirect
from django.core.urlresolvers import reverse

from django.contrib import messages

from django.views.generic import ListView, DetailView

from django.db.models import Prefetch

import threading
from .models import *
from .forms import (UploadFileForm,)

import csv, os

TEMP_PATH = os.getcwd()

def home(request):

    ctx = {'num_docs': File.objects.all().count()}
    return render(request, "base.html", ctx)

def parse_file(_file):

	pass
	
def submit_file(request):

	file_form = UploadFileForm(request.POST or None,
		request.FILES or None)
	if request.method == "POST":
		if file_form.is_valid():
			_file = file_form.cleaned_data['_file']
			parse_file(_file)
	ctx = {'form': file_form}
	return render(request, "submit_file.html", ctx)