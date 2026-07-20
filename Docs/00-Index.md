# TKCC Management System Documentation

Welcome to the central documentation index for the TKCC Management System. This project adheres strictly to Domain-Driven Design (DDD) principles and CQRS architecture across both the backend (Laravel) and frontend (Vue 3).

## 1. Global Architecture
Overview of the physical and logical system design.
- [System Architecture](./Architecture/Architecture.md)

## 2. Bounded Contexts (Modules)
The system is divided into isolated, highly cohesive bounded contexts.

### 2.1 Members Module
Handles the core registry of congregation members, encompassing personal details, contact information, and demographic data.
- [Domain Layer](./Modules/Members/01-Domain-Layer.md)
- [Use Cases](./Modules/Members/02-Use-Cases.md)
- [Sequence & Flow Diagrams](./Modules/Members/03-Sequence-Diagrams.md)

### 2.2 Households Module
Manages family groupings, properties, ownership types, and maps members to their respective households.
- [Domain Layer](./Modules/Households/01-Domain-Layer.md)
- [Use Cases](./Modules/Households/02-Use-Cases.md)
- [Sequence & Flow Diagrams](./Modules/Households/03-Sequence-Diagrams.md)

---
*Generated after Sprint 2: Members & Households Bounded Contexts*
