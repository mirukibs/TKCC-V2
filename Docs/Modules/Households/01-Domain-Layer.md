# Domain Layer: Households Bounded Context

This document outlines the Domain layer of the Households context, built using strict Domain-Driven Design (DDD) principles. The Domain layer is isolated from all framework-specific logic and infrastructure details.

## Domain Model Diagram

![Diagram](./Domain-Layer.svg)

## Key Concepts

1. **Household (Aggregate Root)**: The primary entity for managing a family or group of individuals living together. Validates community assignments and optional leader associations.
2. **No Value Objects**: Following architectural decisions, the Household context does not utilize custom Value Objects, relying instead on primitives and strict entity validation.
3. **Enums (`OwnershipType`)**: Strongly typed constants to denote household property ownership (`owner`, `tenant`, `family`, `other`).
4. **HouseholdRepositoryInterface**: Contract for persisting Household aggregates, decoupling domain logic from the Eloquent implementation.
