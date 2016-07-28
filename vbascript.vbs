Dim TargetRef As Range

Public Function DuplicateCellValue(arr As Variant, value As String) As Boolean
    For i = 1 To UBound(arr)
    	MsgBox CStr(arr(i))
        If Int(arr(i)) = Int(value) Then
            MsgBox "Duplicate Value " & value
            DuplicateCellValue = True
            Exit For
        End If
    Next i
End Function

Public Function RangeAsString(wsname As String, column As Integer)
    Dim SheetRange As Range
    Dim arr() As Variant
    With Worksheets(wsname)
        Set SheetRange = .Range(.Cells(2, 1), .Cells(rows.Count, 1).End(xlUp))
        arr = SheetRange
        For i = 1 To UBound(arr)
            For j = 1 To UBound(arr, 2)
              If arr(i, j) <> "" Then
                RangeAsString = RangeAsString & arr(i, j) & ","
              End If
            Next j
        Next i
    End With
    RangeAsString = Left(RangeAsString, Len(RangeAsString) - 1)
End Function

Private Function InRange(wsname As String, column As Integer, newVal As String) As Boolean
    Dim ColumnRange As Range
    Dim i As Integer, j As Integer
    Dim arr() As Variant
    With Worksheets(wsname)
        Set ColumnRange = .Range(.Cells(2, column), .Cells(rows.Count, column).End(xlUp))
        arr = ColumnRange
        For i = 1 To UBound(arr)
            For j = 1 To UBound(arr, 2)
              If newVal = arr(i, j) Then
                InRange = True
                Exit For
              End If
            Next j
        Next i
    End With
End Function

Private Sub CheckRange(wsname As String, column As Integer, newVal As String, oldVal As String, Target As Range)
    If InRange(wsname, column, newVal) Then
        If oldVal <> "" Then
            Target.value = oldVal & ", " & newVal
        Else
            Target.value = newVal
        End If
    Else
        MsgBox "The ID passed does not exist within the 'Role' sheet ID column."
    End If
End Sub

Private Sub UpdateRelatedRange(wsname As String, column As Integer, newVal As String, oldVal As String, Target As Range)

   	If Len(oldVal) > 0 Then
   		Dim splitArray As Variant
	    'Theres a bug here it disregards the first value before the ","
	    'other than that everything works :D
	    splitArray = split(oldVal, ",")
        If Not DuplicateCellValue(splitArray, newVal) Then
            'We use 1 here because the referential ID column is 
            'letter "A" -> numero cero
            Call CheckRange("Role", 1, newVal, oldVal, Target)
        End If
    Else
    	Target.value = newVal
    End If
End Sub

Private Sub Worksheet_Change(ByVal Target As Range)

    Set TargetRef = Target
    Dim rngDV As Range
    Dim oldVal As String
    Dim newVal As String
    If Target.Count > 1 Then GoTo exitHandler

    On Error Resume Next
        Set rngDV = Cells.SpecialCells(xlCellTypeAllValidation)
    On Error GoTo exitHandler

    If rngDV Is Nothing Then GoTo exitHandler

    If Not Intersect(Target, rngDV) Is Nothing Then
        Application.EnableEvents = False
        newVal = Target.value
        Application.Undo
        oldVal = Target.value
        Select Case Target.column
            Case 15
                If IsNumeric(newVal) Then
                    Call UpdateRelatedRange("Role", 15, newVal, oldVal, TargetRef)
                Else
                    If newVal <> "" Then
                        MsgBox "You must enter an Integer"
                    End If
                    Target.value = ""
                End If
            Case Else
                Target.value = newVal
        End Select
    End If

exitHandler:
        Application.EnableEvents = True
End Sub

Private Sub Worksheet_SelectionChange(ByVal Target As Range)
    Select Case Target.column
        Case 15
            Dim values As String
            '15 = column letter "O"
            With Range(Cells(2, 15), Cells(rows.Count, 15).End(xlUp)).Validation
                values = RangeAsString("Role", 15)
                .Delete
                .Add Type:=xlValidateList, AlertStyle:=xlValidAlertStop, _
                    Operator:=xlEqual, Formula1:=values
                .IgnoreBlank = True
                .InCellDropdown = True
                .InputTitle = ""
                .ErrorTitle = "Role Error Title"
                .InputMessage = "This field accepts integer value(s), that reference the ID column in the 'Role' Sheet. You may select multiples by choosing from the dropdown multiple times."
                .ErrorMessage = "You must enter an Integer"
                .ShowInput = True
                .ShowError = True
            End With
    End Select
End Sub