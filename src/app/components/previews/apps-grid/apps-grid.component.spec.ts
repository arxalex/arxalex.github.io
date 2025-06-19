import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AppsGridComponent } from './apps-grid.component';

describe('ProjectList', () => {
  let component: AppsGridComponent;
  let fixture: ComponentFixture<AppsGridComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AppsGridComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(AppsGridComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
