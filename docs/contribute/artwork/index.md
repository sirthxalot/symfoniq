---
outline: deep
prev:
  text: 'Contribution Guideline'
  link: '/contribute'
---

# Artwork

The artwork can be found within the `art/` directory.

## Gravit Designer

[Gravit Designer] is a free software and runs directly inside a web browser. All you need to do is create an account. I use the software to generate the artwork.

When opening Gravit Designer choose "Open from Computer" and copy the wsl path to the Finder in order to load the file locally. The `art/symfoniq.gvdesign` file contains all the artwork that has been created. Whenever you are finished go to "File" and choose "Save to Local File as".

Each artwork will be created as a page. Whenever you add text don't forget to convert the text to a path you will find the option in the context menu. Make a copy of the text before doing so. Lock and hide the original text before converting it to a path - this makes possible edits easier when needed.

## Fonts

### Roboto

Download and install the [Roboto] Google font. This font is used for regular text.

### Erica One

Download and install the [Erica One] Google font. This font is used for the brand and stylized text.

## Images

### PNG

A `.png` file stands for Portable Network Graphic, which is a popular raster image format (an image made of a grid of colored pixels).

Use a PNG when image quality, sharp lines, or transparency are more important than keeping the file size small.

PNG images enjoy universal browser compatibility across every major web browser in use today. You can confidently use them on the web without worrying about users failing to see them.

### SVG

An `.svg` file stands for Scalable Vector Graphics. Unlike PNG or JPEG files, which are made of a grid of pixels (raster images), an SVG is a text-based format made of mathematical equations, lines, curves, and coordinates (vector images).

The scalability of SVG in web browsers is one of its greatest strengths. Because an SVG is written in XML code using coordinates and math, browsers do not stretch pixels when resizing it. Instead, the browser recomputes and redraws the image instantly, keeping every line and curve perfectly sharp at any resolution.

Do not use SVG for digital photos. A photo contains millions of unique colors and unpredictable gradients, which would require billions of mathematical coordinates, making the SVG file massive and slow to load. Use PNG, JPEG or WebP for photos instead.

### Favicon

I use the [SVG to Favicon Converter] web service to generate a favicon directly from a SVG image.

## Colors

### Main Color "Poison Green"

| Color Name | Hex | RGBA | Preview |
| ---------- | --- | ---- | ------- |
| **Poison Green 1** | `#4DB560` | `77, 181, 96, 1` | <div style="display: inline-flex; width: 60px; height: 20px; background-color: #4DB560"></div> |
| **Poison Green 2** | `#6dcb7e` | `109, 203, 126, 1` | <div style="display: inline-flex; width: 60px; height: 20px; background-color: #6dcb7e"></div> |
| **Poison Green 3** | `#85d494` | `133, 212, 148, 1` | <div style="display: inline-flex; width: 60px; height: 20px; background-color: #85d494"></div> |
| **Poison Green Soft** | `#10B98124` | `16, 185, 129, 0.14` | <div style="display: inline-flex; width: 60px; height: 20px; background-color: #10B98124"></div> |

<!--                    That's all folks!                     -->

[erica one]: https://fonts.google.com/specimen/Erica+One?preview.script=Latn
[gravit designer]: https://gravitdesign.com/index.html
[pantone]: https://pantone.com
[roboto]: https://fonts.google.com/specimen/Roboto
[SVG to Favicon Converter]: https://favicon.io/svg-favicon/
