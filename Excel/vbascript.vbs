Dim TargetRef As Range

Public Function DuplicateCellValue(arr As Variant, value As String) As Boolean
    For i = 0 To UBound(arr)
        If IsNumeric(value) Then
            If Int(arr(i)) = Int(value) Then
                MsgBox "Duplicate Value " & value
                DuplicateCellValue = True
                Exit For
            End If
        Else
            If arr(i) = value Then
                MsgBox "Duplicate Value " & value
                DuplicateCellValue = True
                Exit For
            End If
        End If
    Next i
End Function

Public Function RangeAsString(wsname As String, column As Integer)
    Dim SheetRange As Range
    Dim arr() As Variant
    With ThisWorkbook.Worksheets(wsname)
        Set SheetRange = .Range(.Cells(2, column), .Cells(Rows.Count, column).End(xlUp))
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

Private Function InRange(wsname As String, column As Integer, _
                         newVal As String) As Boolean
    Dim ColumnRange As Range
    Dim i As Integer, j As Integer
    Dim arr() As Variant
    With ThisWorkbook.Worksheets(wsname)
        Set ColumnRange = .Range(.Cells(2, column), .Cells(Rows.Count, column).End(xlUp))
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

Private Sub CheckRange(wsname As String, refcol As Integer, _
                       newVal As String, oldVal As String, _
                       Target As Range)
    If InRange(wsname, refcol, newVal) Then
        If oldVal <> "" Then
            Target.value = oldVal & ", " & newVal
        End If
    Else
        MsgBox "The ID passed does not exist within the " & "'" & wsname & "'" & " sheet ID column."
    End If
End Sub

Private Sub UpdateRelatedRange(wsname As String, refcol As Integer, _
                               newVal As String, oldVal As String, _
                               Target As Range)
    If Len(oldVal) > 0 Then
        Dim splitArray As Variant
        splitArray = Split(oldVal, ",")
        If Not DuplicateCellValue(splitArray, newVal) Then
            'We use 1 here because the referential ID column is
            'letter "A" -> numero cero
            Call CheckRange(wsname, refcol, newVal, oldVal, Target)
        End If
    'First value, so we don't have to worry about multiples
    Else
        Target.value = newVal
    End If
End Sub

Private Sub HandleReferentialColumn(ws As String, refcol As Integer, _
                                    newVal As String, oldVal As String, _
                                    Target As Range, _
                                    Optional numsonly As Boolean)
    If numsonly Then
        If IsNumeric(newVal) Then
            Call UpdateRelatedRange(ws, refcol, newVal, oldVal, Target)
        Else
            MsgBox "You must enter an Integer"
        End If
    Else
        Call UpdateRelatedRange(ws, refcol, newVal, oldVal, Target)
    End If
End Sub

Private Sub Worksheet_Change(ByVal Target As Range)

    On Error GoTo exitHandler
        Application.EnableEvents = False
    On Error GoTo exitHandler
    
    Set TargetRef = Target
    Dim rngDV As Range
    Dim oldVal As String
    Dim newVal As String
    'We have more than a single cell = normal w/ reg. error handling.
    If Target.Count > 1 Then GoTo exitHandler

    On Error Resume Next
        Set rngDV = Cells.SpecialCells(xlCellTypeAllValidation)
    On Error GoTo exitHandler

    If rngDV Is Nothing Then GoTo exitHandler

    If Not Intersect(Target, rngDV) Is Nothing Then
        newVal = Target.value
        'Revart the value back to get the old value
        Application.Undo
        oldVal = Target.value
        Set TargetRef = Target
        'For being able to use the Del / Esc button on columns that have multiples
        'values set by HandleReferentialColumn etc.
        'Otherwise a blank string and commas would keep getting added...
        If newVal = "" Then
            Target.value = newVal
        Else
            Select Case Target.column
                'Contributing Institution column in "Sheet 1"
                Case 4
                    Call HandleReferentialColumn("CONSTANTS", 1, newVal, oldVal, TargetRef)
                'Type of Digital Artifact column in "Sheet 1"
                Case 13
                    Call HandleReferentialColumn("CONSTANTS", 5, newVal, oldVal, TargetRef)
                'Role column in "Sheet 1"
                Case 14
                    Call HandleReferentialColumn("Role", 1, newVal, oldVal, TargetRef, True)
                'Geographic Location column in "Sheet 1"
                Case 19
                    Call HandleReferentialColumn("Geographic Location", 1, newVal, oldVal, TargetRef, True)
                'Langugage column in "Sheet 1"
                Case 25
                    Call HandleReferentialColumn("CONSTANTS", 8, newVal, oldVal, TargetRef)
                Case Else
                    Target.value = newVal
            End Select
        End If
    End If

exitHandler:
        Application.EnableEvents = True
End Sub

Private Sub MakeDropdown(ws As String, startrow As Integer, _
                         col As Integer, refcol As Integer, _
                         Optional InptTit As String = "", _
                         Optional ErrTit As String = "", _
                         Optional InptMsg As String = "", _
                         Optional ErrMsg As String = "")
    Dim values As String
    With Range(Cells(startrow, col), Cells(Rows.Count, col).End(xlUp)).Validation
        values = RangeAsString(ws, refcol)
        .Delete
        .Add Type:=xlValidateList, AlertStyle:=xlValidAlertStop, _
            Operator:=xlEqual, Formula1:=values
        .IgnoreBlank = True
        .InCellDropdown = True
        .InputTitle = InptTit
        .ErrorTitle = ErrTit
        .InputMessage = InptMsg
        .ErrorMessage = ErrMsg
        .ShowInput = True
        .ShowError = True
    End With
End Sub

Private Sub DeleteValidation(Target As Range)

    On Error Resume Next
    For Each Cell In Target
        Cell.Validation.Delete
    Next Cell
End Sub

Private Sub Worksheet_SelectionChange(ByVal Target As Range)

    Select Case Target.column
        'The format for these arguments are worksheet name,
        'the row the data starts for the dropdown menu, the
        'column the dropdown is being made for, the column
        'number the data starts at

        'Archive - Digital Collection column in "Sheet 1"
        Case 3
            Call DeleteValidation(Target)
            Call MakeDropdown("CONSTANTS", 2, 3, 2)
        'Contributing Institution(s) column in "Sheet 1"
        Case 4
            Call DeleteValidation(Target)
            Call MakeDropdown("CONSTANTS", 2, 4, 1)
        'Type of Content column in "Sheet 1"
        Case 11
            Call DeleteValidation(Target)
            Call MakeDropdown("CONSTANTS", 2, 11, 4)
        'Type of Digital Artifact column in "Sheet 1"
        Case 13
            Call DeleteValidation(Target)
            Call MakeDropdown("CONSTANTS", 2, 13, 5)
        'Role column in "Sheet 1"
        Case 14
            Call DeleteValidation(Target)
            Call MakeDropdown("Role", 2, 14, 1, , , "This field accepts integer values(), that reference the ID column in the 'Role' sheet.", "Your value must be a value within the ID column of the 'Role' Sheet")
        'Geographic Location column in "Sheet 1"
        Case 19
            Call DeleteValidation(Target)
            Call MakeDropdown("Geographic Location", 2, 19, 1)
        'Language column in "Sheet 1"
        Case 25
            Call DeleteValidation(Target)
            Call MakeDropdown("CONSTANTS", 2, 25, 8)
        'File Format column in "Sheet 1"
        Case 26
            Call DeleteValidation(Target)
            Call MakeDropdown("CONSTANTS", 2, 26, 7)
    End Select
End Sub

Private Sub Workbook_BeforeClose(Cancel As Boolean)
    Dim strkey As Variant, rng As Range
    Dim Deletions As Scripting.Dictionary
    Set Deletions = New Scripting.Dictionary

    Deletions.Add Key:="Sheet 1", Item:=Array(3, 4, 11, 13, 14, 19, 25, 26)
    Deletions.Add Key:="Role", Item:=Array(2)
    For Each strkey In Deletions.Keys()
        With ThisWorkbook.Worksheets(strkey)
            For Each col In Deletions(strkey)
                Set rng = .Range(.Cells(2, col), .Cells(Rows.Count, col).End(xlUp))
                DeleteValidation (rng)
            Next col
        End With
    Next
    ThisWorkbook.Close
End Sub