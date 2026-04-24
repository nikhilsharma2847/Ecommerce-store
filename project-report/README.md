# Project Report Package

This folder contains the academic project report assets for **A4 print** submission.

## Contents

- **`project-report.md`** — Master source (edit here, then regenerate outputs).
- **`generate_report.py`** — Builds DOCX + PDF (Times-based typography, larger body text, page footers).
- **`images/`** — Screenshots embedded in the report.
- **`source-code/`** — Core source files for annexure / verification.
- **`deliverables/`** — Generated files:
  - **`project-report.docx`** — Word (14pt body, Times New Roman).
  - **`project-report.pdf`** — Standalone report only (no synopsis embedded).
  - **`project-report-complete-with-synopsis.pdf`** — Front matter + **Synopsis Jai.pdf** + remainder of report (for full binder).
  - **`Synopsis Jai.pdf`** — Copy of synopsis for printing.

## Regenerate DOCX/PDF

From `project-report/`:

```powershell
python .\generate_report.py
```

After editing `project-report.md`, always run the script so **DOCX** and **PDF** stay in sync.

## Typography (current)

- **PDF body:** 13pt Times-Roman, ~20pt leading, A4 with ~1" margins, footer with page number.
- **DOCX body:** 14pt Times New Roman, line spacing ~1.2×.
