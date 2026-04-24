from pathlib import Path

ROOT = Path(r"c:\xampp\htdocs\Ecommerce Website")
REPORT_MD = ROOT / "project-report" / "project-report.md"

FILES = [
    "config.php",
    "database.sql",
    "index.php",
    "product.php",
    "cart.php",
    "checkout.php",
    "account.php",
    "admin/login.php",
    "admin/manage_products.php",
    "admin/manage_orders.php",
]

# Keep in-report code concise (not more than ~20 pages total)
MAX_LINES_PER_FILE = 35


def infer_lang(path: str) -> str:
    if path.endswith(".php"):
        return "PHP"
    if path.endswith(".sql"):
        return "SQL"
    if path.endswith(".css"):
        return "CSS"
    return "TEXT"


def build_appendix() -> str:
    chunks = []
    for rel in FILES:
        fpath = ROOT / rel
        if not fpath.exists():
            chunks.append(f"### File: {rel}\n\n    [Missing file]\n")
            continue
        all_lines = fpath.read_text(encoding="utf-8", errors="ignore").splitlines()
        content = all_lines[:MAX_LINES_PER_FILE]
        chunks.append(f"### MAIN CODE FILE: {rel}\n\nLanguage: {infer_lang(rel)}\n")
        chunks.append(f"Code (first {len(content)} lines shown):\n")
        # Indented format works with current report generator
        for line in content:
            chunks.append(f"    {line}")
        if len(all_lines) > len(content):
            chunks.append("    ... [truncated for report size control]")
        chunks.append("\n")
    return "\n".join(chunks)


def main():
    md = REPORT_MD.read_text(encoding="utf-8")
    start_marker = "<!-- SOURCE_CODE_START -->"
    end_marker = "<!-- SOURCE_CODE_END -->"
    s = md.find(start_marker)
    e = md.find(end_marker)
    if s == -1 or e == -1 or e < s:
        raise RuntimeError("Source code markers not found in report markdown")

    s_end = s + len(start_marker)
    new_body = "\n\n" + build_appendix() + "\n"
    out = md[:s_end] + new_body + md[e:]
    REPORT_MD.write_text(out, encoding="utf-8")
    print("Injected full source code appendix into project-report.md")


if __name__ == "__main__":
    main()

