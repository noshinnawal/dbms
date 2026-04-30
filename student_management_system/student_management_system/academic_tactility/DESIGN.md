---
name: Academic Tactility
colors:
  surface: '#f5faff'
  surface-dim: '#d2dbe2'
  surface-bright: '#f5faff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#ecf5fc'
  surface-container: '#e6eff6'
  surface-container-high: '#e0e9f0'
  surface-container-highest: '#dbe4eb'
  on-surface: '#141d22'
  on-surface-variant: '#404849'
  inverse-surface: '#293237'
  inverse-on-surface: '#e9f2f9'
  outline: '#707979'
  outline-variant: '#c0c8c8'
  surface-tint: '#396568'
  primary: '#396568'
  on-primary: '#ffffff'
  primary-container: '#ccfbfe'
  on-primary-container: '#497578'
  inverse-primary: '#a1cfd2'
  secondary: '#884e4d'
  on-secondary: '#ffffff'
  secondary-container: '#feb4b1'
  on-secondary-container: '#7a4342'
  tertiary: '#73584f'
  on-tertiary: '#ffffff'
  tertiary-container: '#ffefea'
  on-tertiary-container: '#84685e'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#bcebee'
  primary-fixed-dim: '#a1cfd2'
  on-primary-fixed: '#002022'
  on-primary-fixed-variant: '#1f4d50'
  secondary-fixed: '#ffdad8'
  secondary-fixed-dim: '#feb4b1'
  on-secondary-fixed: '#360d0e'
  on-secondary-fixed-variant: '#6c3737'
  tertiary-fixed: '#ffdbcf'
  tertiary-fixed-dim: '#e1bfb4'
  on-tertiary-fixed: '#2a1710'
  on-tertiary-fixed-variant: '#594139'
  background: '#f5faff'
  on-background: '#141d22'
  surface-variant: '#dbe4eb'
typography:
  h1:
    fontFamily: Lexend
    fontSize: 40px
    fontWeight: '700'
    lineHeight: '1.2'
  h2:
    fontFamily: Lexend
    fontSize: 32px
    fontWeight: '600'
    lineHeight: '1.3'
  h3:
    fontFamily: Lexend
    fontSize: 24px
    fontWeight: '600'
    lineHeight: '1.4'
  body-lg:
    fontFamily: Lexend
    fontSize: 18px
    fontWeight: '400'
    lineHeight: '1.6'
  body-md:
    fontFamily: Lexend
    fontSize: 16px
    fontWeight: '400'
    lineHeight: '1.6'
  label-sm:
    fontFamily: Lexend
    fontSize: 14px
    fontWeight: '500'
    lineHeight: '1.4'
    letterSpacing: 0.02em
  caption:
    fontFamily: Lexend
    fontSize: 12px
    fontWeight: '400'
    lineHeight: '1.4'
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  unit: 8px
  xs: 4px
  sm: 8px
  md: 16px
  lg: 24px
  xl: 48px
  gutter: 24px
  margin: 32px
---

## Brand & Style

This design system is built upon the principles of **Neomorphism (Soft UI)**, specifically tailored for the academic environment of a School Management System (SMS). The personality is authoritative yet approachable, replacing the sterile flatness of traditional administrative software with a tactile, organic interface that feels physically responsive to user interaction.

The system focuses on "soft" depth—where elements appear to be extruded from or recessed into the background surface. This creates a sense of physical permanence and reliability, crucial for tools handling student data and institutional workflows. The aesthetic avoids harsh lines in favor of light and shadow, resulting in a UI that reduces visual fatigue while maintaining a futuristic, premium feel.

## Colors

The color strategy for this design system leverages the provided palette to define the physical "planes" of the interface. 

*   **Surface Foundation:** #CDD6DD acts as the universal background. In neomorphism, elements must share the same color as the background to create the illusion of being part of the same physical material.
*   **Shadow Dynamics:** To achieve depth, two specific shadow shades are used: a darker tint (#AEB6BC) for the "recess" and a lighter, luminous tint (#ECF6FE) for the "highlight."
*   **Action & Emphasis:** #CCFBFE is reserved for high-visibility highlights or "glow" effects on active states. #CD8987 and #CDACA1 provide warm, humanistic contrasts for specialized modules like faculty portals or alert notifications.

## Typography

This design system utilizes **Lexend** as the sole typeface. Lexend was specifically designed to reduce visual stress and improve reading proficiency, making it the ideal choice for an Academia SMS where users (teachers, students, and admins) process high volumes of alphanumeric data.

The typographic hierarchy is clean and spacious. Given the 3D nature of the UI elements, text remains strictly flat (2D) to ensure maximum legibility against the "curved" surfaces of the containers. High-contrast ink colors are used for text to ensure accessibility standards are met against the mid-tone background.

## Layout & Spacing

This design system employs a **Fixed Grid** model with a heavy emphasis on "negative space as breathing room." Neomorphic elements require more internal and external padding than flat elements to prevent their shadows from overlapping and creating visual mud.

A standard 12-column layout is used for desktop views, but components are granted significant margin (minimum 24px) to ensure the 3D "extrusion" effect is clearly visible. Spacing follows an 8px rhythmic scale to maintain mathematical consistency across all responsive breakpoints.

## Elevation & Depth

Depth is the primary communicator of hierarchy in this design system. Instead of using traditional Z-index layering or translucent overlays, we use **Dual Shadows** to simulate a light source coming from the Top-Left (135 degrees).

*   **Raised (Convex):** Used for cards and buttons. Created with a dark shadow on the bottom-right and a light highlight on the top-left.
*   **Sunken (Concave):** Used for input fields, search bars, and checkboxes. Created by reversing the shadows (inner shadows): dark on the top-left and light on the bottom-right.
*   **Pressed State:** When a "Raised" element is clicked, it transitions to a "Sunken" state, providing immediate tactile feedback to the user.

## Shapes

The shape language is consistently **Rounded**. Sharp 90-degree corners are strictly prohibited as they break the soft-to-the-touch metaphor of neomorphism. 

The standard radius is 0.5rem (8px), but for larger layout containers and cards, the radius increases to 1rem (16px) or 1.5rem (24px). This softness ensures that shadows wrap naturally around the corners, enhancing the 3D effect. All interactive elements (buttons, inputs) must maintain a consistent corner radius to feel like they were molded from the same material.

## Components

### Buttons
Buttons appear as **Raised** extrusions. On hover, the shadow intensity increases slightly. On click, the button transitions to a **Sunken** state to mimic a physical "push." Primary actions can use a subtle inner glow of #CCFBFE.

### Input Fields
Inputs are always **Sunken** (concave). This provides a clear visual cue that the area is a container for data entry. The text cursor and active borders use #CD8987 to provide a professional accent.

### Cards & Containers
Standard dashboard modules are housed in **Raised** cards. To maintain a clean look, cards do not use borders; their boundaries are defined entirely by their soft shadows.

### Chips & Tags
Chips use the tertiary color #CDACA1 as a flat fill with a very subtle 1px "inner" shadow to appear slightly recessed, distinguishing them from the main navigational buttons.

### Checkboxes & Radios
These are small **Sunken** squares or circles. When "Checked," a small "Raised" pill or dot appears inside them, creating a satisfying multi-layered tactile effect.

### Selection States (Navigation)
Active items in a sidebar or menu are indicated by a transition from a flat surface to a **Sunken** "well," making the selected item look like it has been pressed into the surface.