# TKCC Management System Documentation

Welcome to the central documentation index for the TKCC Management System. This project adheres strictly to Domain-Driven Design (DDD) principles and CQRS architecture across both the backend (Laravel) and frontend (Vue 3).

## 1. Global Architecture
Overview of the physical and logical system design.
- [System Architecture](./Architecture/Architecture.md)

## 2. Bounded Contexts (Modules)
The system is divided into isolated, highly cohesive bounded contexts. Below is the documentation for each module.

### [Members Module](./Members.md)
Handles the core registry of congregation members, encompassing personal details, contact information, and demographic data.
- Domain Layer
- Use Cases
- Sequence & Flow Diagrams

### [Households Module](./Households.md)
Manages family groupings, properties, ownership types, and maps members to their respective households.
- Domain Layer
- Use Cases
- Sequence & Flow Diagrams

### [Communities Module](./Communities.md)
Manages the regional community groupings within zones. Allows defining administrative areas mapping multiple households.
- Domain Layer
- Use Cases
- Sequence & Flow Diagrams

---
*Generated after Sprint 2: Members, Households, and Communities Bounded Contexts*
