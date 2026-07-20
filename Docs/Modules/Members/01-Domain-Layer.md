# Domain Layer: Members Bounded Context

This document outlines the Domain layer of the Members context, built using strict Domain-Driven Design (DDD) principles. The Domain layer is isolated from all framework-specific logic and infrastructure details.

## Domain Model Diagram

![Diagram](./Domain-Layer.svg)

## Key Concepts

1. **Member (Aggregate Root)**: The primary entry point for managing a member's state. It ensures that any modifications to a member leave the entity in a valid state. Members contain a reference to their `household_id`.
2. **Value Objects (`FullName`, `PhoneNumber`)**: Immutable objects defined by their attributes rather than identity. They contain their own validation logic (e.g., `PhoneNumber` ensures the number matches valid Tanzanian mobile formats).
3. **Enums (`Gender`, `MaritalStatus`, `EmploymentStatus`)**: Strongly typed constants that replace string constants, preventing invalid states at the language level.
4. **MemberRepositoryInterface**: A contract that the Infrastructure layer must implement. The Domain dictates *what* is needed to save a Member, not *how* it's saved.
