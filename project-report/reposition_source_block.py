from pathlib import Path
import re

REPORT_MD = Path(r"c:\xampp\htdocs\Ecommerce Website\project-report\project-report.md")

START = "<!-- SOURCE_CODE_START -->"
END = "<!-- SOURCE_CODE_END -->"
SECTION_HEADER = "## 8.11 Full Source Code Listing (Extended Annexure)"
SECTION_INTRO = "The following section includes full source code listings from core project files for academic evaluation."
CH9_MARK = "# Chapter 9 — Hosting and Deployment on Shared Hosting (cPanel)"


def main():
    text = REPORT_MD.read_text(encoding="utf-8")

    s = text.find(START)
    e = text.find(END)
    if s == -1 or e == -1 or e < s:
        raise RuntimeError("Source block markers not found")
    e_end = e + len(END)
    block = text[s:e_end]

    # Remove existing block and duplicated 8.11 heading/intro occurrences
    text = text[:s] + text[e_end:]
    text = re.sub(
        r"## 8\.11 Full Source Code Listing \(Extended Annexure\)\n\nThe following section includes full source code listings from core project files for academic evaluation\.\n\n",
        "",
        text,
        flags=re.MULTILINE,
    )

    ch9_idx = text.find(CH9_MARK)
    if ch9_idx == -1:
        raise RuntimeError("Chapter 9 marker not found")

    insertion = (
        f"{SECTION_HEADER}\n\n"
        f"{SECTION_INTRO}\n\n"
        f"{block}\n\n"
        f"\\newpage\n\n"
    )

    text = text[:ch9_idx] + insertion + text[ch9_idx:]
    REPORT_MD.write_text(text, encoding="utf-8")
    print("Repositioned source code block into Chapter 8 section.")


if __name__ == "__main__":
    main()

