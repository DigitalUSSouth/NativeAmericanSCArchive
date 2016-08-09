    PermissionRequiredMixin, UserPassesTestMixin)

from django.core.urlresolvers import reverse

class ProtectedNASCAMixin(UserPassesTestMixin):

    raise_exception = True
    login_url = reverse("login")
    redirect_field_name = "next"

    def test_func(self, user):

        return all([user.is_authenticated,
            user.is_superuser or user.is_staff,])

class AccountApprovalAjaxView(BaseAjaxView):

    permission_required = "user.can_add_user"

class AccountDenyAjaxView(BaseAjaxView):

    permission_required = "user.can_delete_user"