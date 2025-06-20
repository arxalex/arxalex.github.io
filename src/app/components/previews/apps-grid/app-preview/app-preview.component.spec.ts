import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AppPreviewComponent } from './app-preview.component';

describe('Project', () => {
  let component: AppPreviewComponent;
  let fixture: ComponentFixture<AppPreviewComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AppPreviewComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(AppPreviewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
